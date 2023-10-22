<?php

use Covaleski\DataEncoding\Encoder;

class Base4Test extends Encoder
{
    protected static array $alphabet = ['A', 'B', 'C', 'D'];
    protected static int $base = 4;
    protected static bool $isCaseSensitive = true;
}
