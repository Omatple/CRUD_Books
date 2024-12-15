<?php

use App\Database\AuthorRepository;
use App\Utils\AuthorValidator;
use App\Utils\Navigation;

session_start();
require __DIR__ . "/../../vendor/autoload.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id && ($image = AuthorRepository::fetchImageById($id))) {
    AuthorRepository::removeById($id);
    AuthorValidator::deleteOldImage($image);
    $_SESSION["message"] = "Author deleted successfully.";
}

Navigation::redirectTo("authors.php");
