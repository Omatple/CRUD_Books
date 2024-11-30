<?php

namespace App\Utils;

class InputValidator
{
    public static function  isValidLength(string $input, int $min, int $max): bool
    {
        return strlen($input) >= $min && strlen($input) <= $max;
    }

    public static function  sanitize(string $input): string
    {
        return htmlspecialchars(trim($input));
    }
}
