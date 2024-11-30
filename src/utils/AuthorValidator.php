<?php

namespace App\Utils;

use App\Database\Author;

class AuthorValidator extends ImageProcessor
{
    public static function isValidName(string $name): bool
    {
        $minChars = 3;
        $maxChars = 80;
        if (!InputValidator::isValidLength($name, $minChars, $maxChars)) {
            $_SESSION["error_name"] = "Name must be between $minChars and $maxChars";
            return false;
        }
        return true;
    }

    public static function isValidSurname(string $surname): bool
    {
        $minChars = 8;
        $maxChars = 100;
        if (!InputValidator::isValidLength($surname, $minChars, $maxChars)) {
            $_SESSION["error_surname"] = "Name must be between $minChars and $maxChars";
            return false;
        }
        return true;
    }

    public static function isSentAnyFile(int $errorCode): bool
    {
        return parent::isValidCodeError($errorCode);
    }

    public static function isValidImageAuthor(array $imageData): bool
    {
        return parent::isValidImage($imageData);
    }

    public static function  canMoveImage(string $from, string $to): bool
    {
        return parent::canMove($from, $to);
    }

    public static function getUniqueNameImage(string $nameImage): string
    {
        return parent::generateUniqueName($nameImage, "authors");
    }

    public static function deleteOldImage(string $oldImage): bool
    {
        return parent::deleteLastImage($oldImage, "authors");
    }

    public static function isUniqueFullName(string $name, string $surname, ?int $id = null): bool
    {
        if (!Author::isUniqueFullName($name, $surname, $id)) {
            $_SESSION["error_fullName"] = "This full name already exists.";
            return false;
        }
        return true;
    }

    public static function isValidCountry(string $country): bool
    {
        if (!in_array($country, array_map(fn($country) => $country->toString(), Countries::cases()))) {
            $_SESSION["error_country"] = "Please enter a valid country";
            return false;
        }
        return true;
    }
}
