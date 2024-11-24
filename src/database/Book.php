<?php

namespace App\database;

use App\utils\ImageConstants;

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
            [":f" => $field, ":v" => $value]
            :
            [":f" => $field, ":v" => $value, ":i" => $id];
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
    public function setCover(?string $cover = null)
    {
        $this->cover = ($cover === null) ? "img/" . ImageConstants::DEFAULT_COVER_FILENAME : $cover;

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
