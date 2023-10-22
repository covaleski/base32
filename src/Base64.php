<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data in base64.
 * 
 * Implementation of RFC 4648 section 4.
 * 
 * @see https://datatracker.ietf.org/doc/html/rfc4648#section-4
 */
class Base64 extends Encoder
{
    protected static array $alphabet = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
        'w', 'x', 'y', 'z', '0', '1', '2', '3',
        '4', '5', '6', '7', '8', '9', '+', '/',
    ];
    protected static int $base = 64;
    protected static bool $isCaseSensitive = true;
}