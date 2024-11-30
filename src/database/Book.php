<?php

namespace App\Database;

use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

require __DIR__ . "/../../vendor/autoload.php";

class Book extends QueryExecutor
{
    private int $id;
    private string $title;
    private string $synopsis;
    private string $cover;
    private int $author_id;

    public static function read(): array
    {
        return parent::executeQuery("SELECT books.*, authors.name, authors.surname FROM books, authors WHERE author_id = authors.id ORDER BY name", "Failed retreiving books")->fetchAll();
    }

    public function create(): void
    {
        $parametersStatement = [
            ":t" => $this->title,
            ":s" => $this->synopsis,
            ":c" => $this->cover,
            ":a" => $this->author_id,
        ];
        parent::executeQuery(
            "INSERT INTO books (title, synopsis, cover, author_id) VALUES (:t, :s, :c, :a)",
            "Failed to create book '{$this->title}'",
            $parametersStatement
        );
    }

    public static function delete(int $id): void
    {
        parent::executeQuery("DELETE FROM books WHERE id = :i", "Failed to delete book with ID '$id'", [":i" => $id]);
    }

    public static function isFieldUnique(string $field, string $value, ?int $id = null): bool
    {
        $paremetersStatement = ($id === null) ?
            [":v" => $value]
            :
            [":v" => $value, ":i" => $id];
        $query = ($id === null) ?
            "SELECT * FROM books WHERE $field = :v"
            :
            "SELECT * FROM books WHERE $field = :v AND id <> :i";
        return !parent::executeQuery(
            $query,
            "Failed checking if field '$field' of books with value '$value' is unique",
            $paremetersStatement
        )->fetchColumn();
    }

    public static function  getCoverById(int $id): string|bool
    {
        return parent::executeQuery("SELECT cover FROM books WHERE id = :i", "Failed getting cover of book with ID '$id'", [":i" => $id])->fetchColumn();
    }

    public function update(int $id): void
    {
        $parametersStatement = [
            ":i" => $id,
            ":t" => $this->title,
            ":s" => $this->synopsis,
            ":c" => $this->cover,
            ":a" => $this->author_id,
        ];
        parent::executeQuery(
            "UPDATE books SET title = :t, synopsis = :s, cover = :c, author_id = :a WHERE id = :i",
            "Failed to update book '$id'",
            $parametersStatement
        );
    }

    public static function  generateFakeBooks(int $amount): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        for ($i = 0; $i < $amount; $i++) {
            $title = $faker->title() . $faker->unique()->word();
            $synopsis = $faker->text();
            $cover = "img/books/" . $faker->fakeImg(dir: __DIR__ . "/../../public/img/books/", width: 640, height: 640, fullPath: false, text: strtoupper(substr($title, 0, 2)), backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]);
            $author_id = $faker->randomElement(Author::getAllIds());
            (new Book)
                ->setTitle(ucwords($title))
                ->setSynopsis($synopsis)
                ->setCover($cover)
                ->setAuthor_id($author_id)
                ->create();
        }
    }

    public static function restoreBooks(): void
    {
        parent::executeQuery("DELETE FROM books", "Failed to restore table books");
        parent::executeQuery("ALTER TABLE books AUTO_INCREMENT = 1", "Failed to restore auto increment of the table books");
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of synopsis
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set the value of synopsis
     *
     * @return  self
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get the value of cover
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set the value of cover
     *
     * @return  self
     */
    public function setCover(string $cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get the value of author_id
     */
    public function getAuthor_id()
    {
        return $this->author_id;
    }

    /**
     * Set the value of author_id
     *
     * @return  self
     */
    public function setAuthor_id($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }
}
