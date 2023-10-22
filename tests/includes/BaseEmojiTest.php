<?php

use Covaleski\DataEncoding\Encoder;

class BaseEmojiTest extends Encoder
{
    protected static array $alphabet = [
        '😎', '😔', '😳', '💀', '🤡', '👀', '✊', '💅',
    ];
    protected static int $base = 8;
    protected static string $alphabetEncoding = 'UTF-8';
    protected static bool $isCaseSensitive = false;
}
