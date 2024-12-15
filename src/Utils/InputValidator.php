<?php

namespace App\Utils;

class InputValidator
{
    public static function isValidLength(string $input, int $min, int $max): bool
    {
        $length = strlen($input);
        return $length >= $min && $length <= $max;
    }

    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
