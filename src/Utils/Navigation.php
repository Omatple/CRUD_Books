<?php

namespace App\Utils;

class Navigation
{
    public static function redirectTo(string $url): void
    {
        header("Location: $url");
        exit();
    }

    public static function refreshPage(): void
    {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}
