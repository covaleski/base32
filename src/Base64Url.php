<?php

namespace Covaleski\DataEncoding;

/**
 * Encodes and decodes data in base64url.
 * 
 * Uses a URL and filename safe alphabet version of default base64's.
 * 
 * Implementation of RFC 4648 section 5.
 * 
 * @see https://datatracker.ietf.org/doc/html/rfc4648#section-5
 */
class Base64Url extends Base64
{
    protected static array $alphabet = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
        'w', 'x', 'y', 'z', '0', '1', '2', '3',
        '4', '5', '6', '7', '8', '9', '-', '_',
    ];
}