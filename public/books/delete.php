<?php

use App\Database\BookRepository;
use App\Utils\BookValidator;
use App\Utils\Navigation;

session_start();

require __DIR__ . "/../../vendor/autoload.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id && $cover = BookRepository::fetchCoverById($id)) {
    BookValidator::deleteBook($id);
    BookValidator::deleteOldCover($cover);
}

Navigation::redirectTo("books.php");
