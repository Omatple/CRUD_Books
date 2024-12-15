<?php

namespace App\Utils;

use App\Database\AuthorRepository;
use App\Database\BookRepository;

class BookValidator extends ImageProcessor
{
    public static function isValidTitle(string $title): bool
    {
        $minChars = 3;
        $maxChars = 100;

        if (!InputValidator::isValidLength($title, $minChars, $maxChars)) {
            $_SESSION["error_title"] = "The title must be between $minChars and $maxChars characters.";
            return false;
        }

        return true;
    }

    public static function isValidSynopsis(string $synopsis): bool
    {
        $minChars = 20;
        $maxChars = 200;

        if (!InputValidator::isValidLength($synopsis, $minChars, $maxChars)) {
            $_SESSION["error_synopsis"] = "The synopsis must be between $minChars and $maxChars characters.";
            return false;
        }

        return true;
    }

    public static function isValidAuthorId(int $id): bool
    {
        if (!in_array($id, AuthorRepository::fetchAllIds())) {
            $_SESSION["error_authorId"] = "Please select a valid author.";
            return false;
        }

        return true;
    }

    public static function isValidCover(array $imageData): bool
    {
        return parent::isValidImage($imageData);
    }

    public static function isFileUploaded(int $errorCode): bool
    {
        return parent::isValidErrorCode($errorCode);
    }

    public static function moveNewCover(string $from, string $to): bool
    {
        return parent::moveFile($from, $to);
    }

    public static function generateUniqueCoverName(string $coverName): string
    {
        return parent::generateUniqueFileName($coverName, "books");
    }

    public static function deleteOldCover(string $oldCoverName): bool
    {
        return parent::deletePreviousImage(basename($oldCoverName), "books");
    }

    public static function isTitleUnique(string $title, ?int $id = null): bool
    {
        if (!BookRepository::isUniqueField("title", $title, $id)) {
            $_SESSION["error_title"] = "This book title already exists.";
            return false;
        }

        return true;
    }

    public static function deleteBook(int $id): void
    {
        BookRepository::removeById($id);
        $_SESSION["message"] = "The book has been successfully deleted.";
    }
}
