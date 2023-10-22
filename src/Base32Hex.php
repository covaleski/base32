<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data in base32hex.
 * 
 * Replaces the default base32 alphabet by extending the base16's.
 * 
 * Implementation of RFC 4648 section 7.
 * 
 * @see https://datatracker.ietf.org/doc/html/rfc4648#section-7
 */
class Base32Hex extends Base32
{
    protected static array $alphabet = [
        '0', '1', '2', '3', '4', '5', '6', '7',
        '8', '9', 'A', 'B', 'C', 'D', 'E', 'F',
        'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
    ];
}