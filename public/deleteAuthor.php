<?php

use App\Database\Author;
use App\Utils\AuthorValidator;
use App\Utils\Navigation;

session_start();
require __DIR__ . "/../vendor/autoload.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id && ($image = Author::getImageById($id))) {
    Author::delete($id);
    AuthorValidator::deleteOldImage($image);
    $_SESSION["message"] = "Author deleted successly.";
}
Navigation::redirectTo("authors.php");
