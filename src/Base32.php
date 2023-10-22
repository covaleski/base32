<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data in base32.
 * 
 * Implementation of RFC 4648 section 6.
 * 
 * @see https://datatracker.ietf.org/doc/html/rfc4648#section-6
 */
class Base32 extends Encoder
{
    protected static array $alphabet = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z', '2', '3', '4', '5', '6', '7',
    ];
    protected static int $base = 32;
    protected static bool $isCaseSensitive = false;
}