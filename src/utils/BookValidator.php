<?php

namespace App\Utils;

use App\Database\Author;
use App\Database\Book;

class BookValidator extends ImageProcessor
{
    public static function isValidTitle(string $title): bool
    {
        $minChars = 3;
        $maxChars = 100;
        if (!InputValidator::isValidLength($title, $minChars, $maxChars)) {
            $_SESSION["error_title"] = "The title must be between $minChars and $maxChars.";
            return false;
        }
        return true;
    }

    public static function isValidSynopsis(string $synopsis): bool
    {
        $minChars = 20;
        $maxChars = 200;
        if (!InputValidator::isValidLength($synopsis, $minChars, $maxChars)) {
            $_SESSION["error_synopsis"] = "The synopsis must be between $minChars and $maxChars.";
            return false;
        }
        return true;
    }

    public static function isValidAuthorId(int $id): bool
    {
        if (!in_array($id, Author::getAllIds())) {
            $_SESSION["error_authorId"] = "Please select a valid author.";
            return false;
        }
        return true;
    }

    public static function isValidCover(array $imageData): bool
    {
        return parent::isValidImage($imageData);
    }

    public static function  isSentAnyFile(int $errorCode): bool
    {
        return parent::isValidCodeError($errorCode);
    }

    public static function isMoveNewCover(string $from, string $to): bool
    {
        return parent::canMove($from, $to);
    }

    public static function  getUniqueCoverName(string $nameCover): string
    {
        return parent::generateUniqueName($nameCover, "books");
    }

    public static function  deleteOldCover(string $oldCoverName): bool
    {
        return parent::deleteLastImage(basename($oldCoverName), "books");
    }

    public static function  isTitleUnique(string $title, ?int $id = null): bool
    {
        if (!Book::isFieldUnique("title", $title, $id)) {
            $_SESSION["error_title"] = "This title book alrady exits.";
            return false;
        }
        return true;
    }

    public static function deleteBook(int $id): void
    {
        Book::delete($id);
        $_SESSION["message"] = "Book deleted successly.";
    }
}
