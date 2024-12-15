<?php

namespace App\Utils;

use App\Database\AuthorRepository;

class AuthorValidator extends ImageProcessor
{
    public static function isValidName(string $name): bool
    {
        $minChars = 3;
        $maxChars = 80;

        if (!InputValidator::isValidLength($name, $minChars, $maxChars)) {
            $_SESSION["error_name"] = "The name must be between $minChars and $maxChars characters.";
            return false;
        }

        return true;
    }

    public static function isValidSurname(string $surname): bool
    {
        $minChars = 8;
        $maxChars = 100;

        if (!InputValidator::isValidLength($surname, $minChars, $maxChars)) {
            $_SESSION["error_surname"] = "The surname must be between $minChars and $maxChars characters.";
            return false;
        }

        return true;
    }

    public static function isFileUploaded(int $errorCode): bool
    {
        return parent::isValidErrorCode($errorCode);
    }

    public static function isValidImage(array $imageData): bool
    {
        return parent::isValidImage($imageData);
    }

    public static function moveImage(string $from, string $to): bool
    {
        return parent::moveFile($from, $to);
    }

    public static function generateUniqueImageName(string $imageName): string
    {
        return parent::generateUniqueFileName($imageName, "authors");
    }

    public static function deleteOldImage(string $oldImage): bool
    {
        return parent::deletePreviousImage($oldImage, "authors");
    }

    public static function isUniqueFullName(string $name, string $surname, ?int $id = null): bool
    {
        if (!AuthorRepository::isUniqueFullName($name, $surname, $id)) {
            $_SESSION["error_fullName"] = "This full name already exists.";
            return false;
        }

        return true;
    }

    public static function isValidCountry(string $country): bool
    {
        if (!in_array($country, array_map(fn($country) => $country->toString(), Country::cases()))) {
            $_SESSION["error_country"] = "Please select a valid country.";
            return false;
        }

        return true;
    }
}
