<?php

use App\Database\Book;
use App\Utils\BookValidator;
use App\Utils\Navigation;

session_start();

require __DIR__ . "/../vendor/autoload.php";
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id && $image = Book::getCoverById($id)) {
    BookValidator::deleteBook($id);
    BookValidator::deleteOldCover($image);
}

Navigation::redirectTo("books.php");
