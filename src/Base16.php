<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data in base16.
 * 
 * Implementation of RFC 4648 section 8.
 * 
 * @see https://datatracker.ietf.org/doc/html/rfc4648#section-8
 */
class Base16 extends Encoder
{
    protected static array $alphabet = [
        '0', '1', '2', '3', '4', '5', '6', '7',
        '8', '9', 'A', 'B', 'C', 'D', 'E', 'F',
    ];
    protected static int $base = 16;
    protected static bool $isCaseSensitive = false;
}