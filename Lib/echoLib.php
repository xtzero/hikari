<?php

namespace Lib;

class echoLib
{
    public static function echoGreen($text)
    {
        echo "\033[32m{$text}" . PHP_EOL;
    }
}