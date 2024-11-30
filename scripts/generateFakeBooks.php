<?php

use App\Database\Author;
use App\Database\Book;

require __DIR__ . "/../vendor/autoload.php";

do {
    $amount = (int) readline("Write number of books you want create(5-50), or 0 to exit: ");
    if ($amount === 0) exit("\nExit for request of user..." . PHP_EOL);
    if ($amount < 5 || $amount > 50) echo "\nERROR: Please write a number between 5 and 50." . PHP_EOL;
} while ($amount < 5 || $amount > 50);

Book::restoreBooks();
Author::restoreAuthors();
Author::generateFakeAuthors();
Book::generateFakeBooks($amount);
echo "\n$amount fake books has been created successly." . PHP_EOL;
