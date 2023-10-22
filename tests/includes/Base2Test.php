<?php

use Covaleski\DataEncoding\Encoder;

class Base2Test extends Encoder
{
    protected static array $alphabet = ['0', '1'];
    protected static int $base = 2;
    protected static bool $isCaseSensitive = true;
}
