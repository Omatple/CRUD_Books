<?php

use App\Database\AuthorRepository;
use App\Database\BookRepository;

require __DIR__ . "/../vendor/autoload.php";

do {
    $amount = (int) readline("Write the number of books you want to create (5-50), or 0 to exit: ");
    if ($amount === 0) exit("\nExiting at user request..." . PHP_EOL);
    if ($amount < 5 || $amount > 50) echo "\nERROR: Please enter a number between 5 and 50." . PHP_EOL;
} while ($amount < 5 || $amount > 50);

BookRepository::resetBooks();
AuthorRepository::resetAuthors();
AuthorRepository::generateFakeAuthors();
BookRepository::generateFakeBooks($amount);

echo "\n$amount fake books have been successfully created." . PHP_EOL;
