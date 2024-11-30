<?php

namespace App\Utils;

class Navigation
{
    public static function redirectTo(string $page): void
    {
        header("Location: $page");
        exit();
    }

    public static function refresh(): void
    {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}
