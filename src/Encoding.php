<?php

namespace Covaleski\DataEncoding;

/**
 * Stores configuration for a specific data encoding style.
 */
class Encoding
{
    /**
     * Number of bits each output CHARACTER represents.
     */
    public int $characterBits;

    /**
     * Number of characters each CHUNK of output must have.
     * 
     * Incomplete chunks are padded with the padding character.
     */
    public int $outputChunkLength;

    /**
     * Create the encoding instance.
     */
    public function __construct(
        /** Enconding alphabet. */
        public array $alphabet,
        /** Alphabet character encoding type. */
        public string $alphabetEncoding,
        /** Encoding base - power of 2. */
        public int $base,
        /** Whether the encoding is case-sensitive or not. */
        public bool $isCaseSensitive,
        /** Padding character. */
        public string $paddingCharacter,
    )
    {
        // Require `$base` to be greater or equal to 2.
        if ($this->base < 2) {
            $msg = '`$base` must be greater or equal to 2. Got %s.';
            throw new \InvalidArgumentException(sprintf($msg, $this->base));
        }
        
        // Calculate how many bits each output character will represent.
        $this->characterBits = log($this->base, 2);

        // Require `$base` to be a power of 2.
        if ($this->characterBits !== intval(floor($this->characterBits))) {
            $msg = '`$base` is not a power of 2.';
            throw new \InvalidArgumentException($msg);
        }

        // Require the alphabet to have `$this->base` numbers.
        if (count($this->alphabet) !== $this->base) {
            $msg = 'Received alphabet of length %s for base %s.';
            $msg = sprintf($msg, count($this->alphabet), $this->base);
            throw new \InvalidArgumentException($msg);
        }

        // Calculate how output characters are chunked.
        // Get the amount of bits represented by the chunk using the LCM of:
        // - The number of bits each output character represents;
        // - 8, which is the number of bits a chunk of unpacked input data has.
        $this->outputChunkLength = $this->lcm($this->characterBits, 8);
        // Divide the bits to get how many output characters it must have.
        $this->outputChunkLength /= $this->characterBits;
    }

    /**
     * Get the greatest common divisor of two numbers.
     */
    protected function gcd(int $a, int $b): int
    {
        return $b === 0 ? $a : $this->gcd($b, $a % $b);
    }

    /**
     * Get the least common multiple of two numbers.
     */
    protected function lcm(int $a, int $b): int
    {
        return ($a * $b) / $this->gcd($a, $b);
    }
}