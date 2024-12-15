<?php

namespace App\Database;

use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

require __DIR__ . "/../../vendor/autoload.php";

class BookRepository extends QueryExecutor
{
    private int $bookId;
    private string $bookTitle;
    private string $bookSynopsis;
    private string $bookCover;
    private int $authorId;

    public static function fetchAll(): array
    {
        return parent::executeQuery(
            "SELECT books.*, authors.name, authors.surname FROM books, authors WHERE author_id = authors.id ORDER BY name", 
            "Error fetching books list"
        )->fetchAll();
    }

    public function save(): void
    {
        $parameters = [
            ":title" => $this->bookTitle,
            ":synopsis" => $this->bookSynopsis,
            ":cover" => $this->bookCover,
            ":authorId" => $this->authorId,
        ];
        parent::executeQuery(
            "INSERT INTO books (title, synopsis, cover, author_id) VALUES (:title, :synopsis, :cover, :authorId)",
            "Error adding new book '{$this->bookTitle}'",
            $parameters
        );
    }

    public static function removeById(int $bookId): void
    {
        parent::executeQuery(
            "DELETE FROM books WHERE id = :id", 
            "Error deleting book with ID '$bookId'", 
            [":id" => $bookId]
        );
    }

    public static function isUniqueField(string $fieldName, string $fieldValue, ?int $excludedId = null): bool
    {
        $parameters = $excludedId === null ? 
            [":value" => $fieldValue] : 
            [":value" => $fieldValue, ":excludedId" => $excludedId];
        $query = $excludedId === null ? 
            "SELECT * FROM books WHERE $fieldName = :value" : 
            "SELECT * FROM books WHERE $fieldName = :value AND id <> :excludedId";

        return !parent::executeQuery(
            $query,
            "Error checking uniqueness of field '$fieldName' with value '$fieldValue'",
            $parameters
        )->fetchColumn();
    }

    public static function fetchCoverById(int $bookId): string|bool
    {
        return parent::executeQuery(
            "SELECT cover FROM books WHERE id = :id", 
            "Error fetching cover for book with ID '$bookId'", 
            [":id" => $bookId]
        )->fetchColumn();
    }

    public static function fetchById(int $bookId): array|bool
    {
        return parent::executeQuery(
            "SELECT * FROM books WHERE id = :id", 
            "Error fetching book with ID '$bookId'", 
            [":id" => $bookId]
        )->fetch();
    }

    public function updateById(int $bookId): void
    {
        $parameters = [
            ":id" => $bookId,
            ":title" => $this->bookTitle,
            ":synopsis" => $this->bookSynopsis,
            ":cover" => $this->bookCover,
            ":authorId" => $this->authorId,
        ];
        parent::executeQuery(
            "UPDATE books SET title = :title, synopsis = :synopsis, cover = :cover, author_id = :authorId WHERE id = :id",
            "Error updating book with ID '$bookId'",
            $parameters
        );
    }

    public static function generateFakeBooks(int $amount): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        for ($i = 0; $i < $amount; $i++) {
            $title = $faker->title() . $faker->unique()->word();
            $synopsis = $faker->text();
            $cover = "img/books/" . $faker->fakeImg(
                dir: __DIR__ . "/../../public/img/books/",
                width: 640,
                height: 640,
                fullPath: false,
                text: strtoupper(substr($title, 0, 2)),
                backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]
            );
            $authorId = $faker->randomElement(AuthorRepository::fetchAllIds());
            (new BookRepository)
                ->setBookTitle(ucwords($title))
                ->setBookSynopsis($synopsis)
                ->setBookCover($cover)
                ->setAuthorId($authorId)
                ->save();
        }
    }

    public static function resetBooks(): void
    {
        parent::executeQuery("DELETE FROM books", "Error clearing books table");
        parent::executeQuery("ALTER TABLE books AUTO_INCREMENT = 1", "Error resetting auto increment");
    }

    public function getBookId()
    {
        return $this->bookId;
    }

    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
        return $this;
    }

    public function getBookTitle()
    {
        return $this->bookTitle;
    }

    public function setBookTitle($bookTitle)
    {
        $this->bookTitle = $bookTitle;
        return $this;
    }

    public function getBookSynopsis()
    {
        return $this->bookSynopsis;
    }

    public function setBookSynopsis($bookSynopsis)
    {
        $this->bookSynopsis = $bookSynopsis;
        return $this;
    }

    public function getBookCover()
    {
        return $this->bookCover;
    }

    public function setBookCover(string $bookCover)
    {
        $this->bookCover = $bookCover;
        return $this;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }
}
