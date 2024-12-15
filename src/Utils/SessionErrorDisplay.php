<?php

namespace App\Utils;

class SessionErrorDisplay
{
    public static function displayError(string $errorKey): void
    {
        if ($errorMessage = $_SESSION["error_$errorKey"] ?? false) {
            echo "<p class='text-sm font-bold text-red-700'>$errorMessage</p>";
            unset($_SESSION["error_$errorKey"]);
        }
    }
}
