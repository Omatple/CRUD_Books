<?php

namespace App\Utils;

class SessionErrorDisplay
{
    public static function  showError(string $errorName): void
    {
        if ($errorMessage = $_SESSION["error_$errorName"] ?? false) {
            echo "<p class='text-sm font-bold text-red-700'>$errorMessage</p>";
            unset($_SESSION["error_$errorName"]);
        }
    }
}
