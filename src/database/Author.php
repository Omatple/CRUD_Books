<?php

namespace App\database;

class Author extends QueryExecutor
{
    private int $id;
    private string $name;
    private string $surname;
    private string $country;

    public static function read(): array
    {
        return parent::executeQuery("SELECT * FROM authors", "Failed retreiving authors")->fetchAll();
    }

    public function create(): void
    {
        $parametersStatement = [
            ":n" => $this->name,
            ":s" => $this->surname,
            ":c" => $this->country,
        ];
        parent::executeQuery(
            "INSERT INTO authors (name, surname, country) VALUES (:n, :s, :c)",
            "Failed to create author '{$this->name}'",
            $parametersStatement
        );
    }

    public static function delete(int $id): void
    {
        parent::executeQuery("DELETE FROM authors WHERE id = :i", "Failed to delete author with ID '$id'", [":i" => $id]);
    }

    public static function isFieldUnique(string $field, string $value, ?int $id = null): bool
    {
        $paremetersStatement = ($id === null) ?
            [":f" => $field, ":v" => $value]
            :
            [":f" => $field, ":v" => $value, ":i" => $id];
        $query = ($id === null) ?
            "SELECT * FROM author WHERE $field = :v"
            :
            "SELECT * FROM author WHERE $field = :v AND id <> :i";
        return !parent::executeQuery(
            $query,
            "Failed checking if field '$field' of authors with value '$value' is unique",
            $paremetersStatement
        )->fetchColumn();
    }

    public function update(int $id): void
    {
        $parametersStatement = [
            ":i" => $id,
            ":n" => $this->name,
            ":s" => $this->surname,
            ":c" => $this->country,
        ];
        parent::executeQuery(
            "UPDATE books SET name = :n, surname = :s, country = :c WHERE id = :i",
            "Failed to update author '$id'",
            $parametersStatement
        );
    }

    public static function getAllIds(): array
    {
        return parent::executeQuery("SELECT id FROM authors", "Failed retraiving IDs of authors")->fetchColumn();
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
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @return  self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}
