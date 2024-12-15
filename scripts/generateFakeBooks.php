<?php

<<<<<<< HEAD
use App\Database\Author;
use App\Database\Book;
=======
use App\Database\AuthorRepository;
use App\Database\BookRepository;
>>>>>>> bc9b800 (feat: Finalización y refactorización completa del proyecto CRUD_Books con documentación detallada)

require __DIR__ . "/../vendor/autoload.php";

do {
<<<<<<< HEAD
    $amount = (int) readline("Write number of books you want create(5-50), or 0 to exit: ");
    if ($amount === 0) exit("\nExit for request of user..." . PHP_EOL);
    if ($amount < 5 || $amount > 50) echo "\nERROR: Please write a number between 5 and 50." . PHP_EOL;
} while ($amount < 5 || $amount > 50);

Book::restoreBooks();
Author::restoreAuthors();
Author::generateFakeAuthors();
Book::generateFakeBooks($amount);
echo "\n$amount fake books has been created successly." . PHP_EOL;
=======
    $amount = (int) readline("Write the number of books you want to create (5-50), or 0 to exit: ");
    if ($amount === 0) exit("\nExiting at user request..." . PHP_EOL);
    if ($amount < 5 || $amount > 50) echo "\nERROR: Please enter a number between 5 and 50." . PHP_EOL;
} while ($amount < 5 || $amount > 50);

BookRepository::resetBooks();
AuthorRepository::resetAuthors();
AuthorRepository::generateFakeAuthors();
BookRepository::generateFakeBooks($amount);

echo "\n$amount fake books have been successfully created." . PHP_EOL;
>>>>>>> bc9b800 (feat: Finalización y refactorización completa del proyecto CRUD_Books con documentación detallada)
