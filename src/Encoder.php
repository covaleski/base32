<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data.
 */
class Encoder
{
    /**
     * Encoding alphabet.
     */
    protected static array $alphabet;

    /**
     * Alphabet character encoding type.
     */
    protected static string $alphabetEncoding = 'ASCII';

    /**
     * Encoding base - power of 2.
     */
    protected static int $base;

    /**
     * Encoder instances.
     */
    protected static array $instances = [];

    /**
     * Whether the encoding is case-sensitive or not.
     */
    protected static bool $isCaseSensitive;

    /**
     * Padding character.
     * 
     * Will be used to fill incomplete output chunks of characters.
     */
    protected static string $paddingCharacter = '=';

    /**
     * Decode a string.
     */
    public static function decode(string $string): string
    {
        // Ignore empty strings.
        if (empty($string)) {
            return '';
        }

        // Get configuration.
        $encoding = static::getEncoding();

        // Ignore lowercase chars if case-insensitive.
        if (!$encoding->isCaseSensitive) {
            $string = mb_strtoupper($string, $encoding->alphabetEncoding);
        }

        // Get the alphabet characters indexes.
        $indexes = [];
        $characters = mb_str_split($string, 1, $encoding->alphabetEncoding);
        foreach ($characters as $i => $character) {
            // Stop on padding.
            if ($character === $encoding->paddingCharacter) {
                break;
            }
            // Get character index.
            $index = array_search($character, $encoding->alphabet);
            if ($index === false) {
                $msg = 'Found invalid character at position %s.';
                throw new \Exception(sprintf($msg, $i));
            }
            $indexes[] = $index;
        }

        // Calculate how many zeroes were used to pad the last encoded bits.
        // If not ignored, these bits will add a NULL character to the string.
        // Encoding example (base32): 'f' => 01100110 => 01100 11000
        // Correct decoding: 01100 11000 => 01100110 => 'f'
        // Wrong decoding: 01100 11000 => 01100110 00000000 => "f\x00"
        $padding = (count($indexes) * $encoding->characterBits) % 8;

        // Turn the n-bit integers into 8-bit integers.
        $bytes = static::regroupBits(
            $indexes,
            $encoding->characterBits,
            8,
            $padding,
        );

        // Pack the bytes into a string.
        $result = pack('C*', ...$bytes);

        return $result;
    }

    /**
     * Encode a string.
     */
    public static function encode(string $string): string
    {
        // Ignore empty strings.
        if (empty($string)) {
            return '';
        }

        // Get configuration.
        $encoding = static::getEncoding();

        // Unpack the string into an array of 8-bit integers.
        $bytes = array_values(unpack('C*', $string));

        // Turn the 8-bit integers into n-bit integers.
        $groups = static::regroupBits($bytes, 8, $encoding->characterBits, 0);
        // Use each n-bit integer to get a character from the alphabet.
        $result = '';
        foreach ($groups as $index) {
            $result .= $encoding->alphabet[$index];
        }

        // Pad the last group if incomplete.
        $result_length = mb_strlen($result, $encoding->alphabetEncoding);
        $overflow = $result_length % $encoding->outputChunkLength;
        if ($overflow > 0) {
            $padding = $encoding->outputChunkLength - $overflow;
            $result .= str_repeat($encoding->paddingCharacter, $padding);
        }

        return $result;
    }

    /**
     * Get the active instance of the current class.
     * 
     * Creates it if necessary.
     */
    protected static function getEncoding(): Encoding
    {
        static::$instances[static::class] ??= new Encoding(
            static::$alphabet,
            static::$alphabetEncoding,
            static::$base,
            static::$isCaseSensitive,
            static::$paddingCharacter,
        );

        return static::$instances[static::class];
    }

    /**
     * Regroup an array of integers that represent a large binary value.
     * 
     * Turns an array of `$from`-bit integers into one of `$to`-bit integers.
     * 
     * Smaller `$to` values create longer arrays and vice-versa.
     * 
     * Remaining bits at the last element are used as high-order bits.
     * 
     * Example (for `$from=8` and `$to=5`):
     * 
     * `[0b01011011, 0b11001110]` => `[0b01011, 0b01111, 0b00111, 0b00000]`
     */
    protected static function regroupBits(
        array $groups,
        int $from,
        int $to,
        int $padding,
    ): array {
        // Get group count.
        $count = count($groups);
    
        // Initialize the result array.
        $result = [];
    
        // Group index.
        $i = 0;
        // Result index.
        $r = 0;
        // Available extracted bits.
        $extracted = 0;
        // Length of available extracted bits.
        $length = 0;
    
        while (true) {
            // Extract incoming groups until we got `$to` bits.
            while ($length < $to && $i < $count) {
                // Move `$from` bits to left.
                $extracted <<= $from;
                // Add new extracted bits.
                $extracted |= $groups[$i];
                // Update length.
                $length += $from;
                // Go to next incoming group.
                $i++;
            }
            // Add the last bits.
            if ($length < $to) {
                // Do not add if these are just trailing zeroes.
                if ($length > $padding) {
                    $result[$r] = $extracted << ($to - $length);
                }
                break;
            }
            // Add an element with the `$to` high-order bits of `$extracted`.
            $offset = $length - $to;
            $result[$r] = $extracted >> $offset;
            // Go to next result group.
            $r++;
            // Remove used bits from `$extracted`.
            $extracted &= (2 ** $offset) - 1;
            $length -= $to;
        }
    
        return $result;
    }
}
