<?php

namespace App\Database;

use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

class Author extends QueryExecutor
{
    private int $id;
    private string $name;
    private string $surname;
    private string $country;
    private string $image;

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
            ":im" => $this->image,
        ];
        parent::executeQuery(
            "INSERT INTO authors (name, surname, country, image) VALUES (:n, :s, :c, :im)",
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
            ":im" => $this->image,
        ];
        parent::executeQuery(
            "UPDATE authors SET name = :n, surname = :s, country = :c, image = :im WHERE id = :i",
            "Failed to update author '$id'",
            $parametersStatement
        );
    }

    public static function isUniqueFullName(string $name, string $surname, ?int $id = null): bool
    {
        $queryId = is_null($id) ? "" : " AND id <> :i";
        return !parent::executeQuery(
            "SELECT * FROM authors WHERE name = :n AND surname = :s$queryId",
            "Failed checking if unique full name",
            is_null($id) ? [
                ":n" => $name,
                ":s" => $surname,
            ] : [
                ":n" => $name,
                ":s" => $surname,
                ":i" => $id,
            ]
        )->fetchColumn();
    }

    public static function getAuthorById(int $id): array|false
    {
        return parent::executeQuery("SELECT * FROM authors WHERE id = :i", "Failed retraive author by id", [
            ":i" => $id,
        ])->fetch();
    }

    public static function  generateFakeAuthors(): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        $amount = 20;
        for ($i = 0; $i < $amount; $i++) {
            $name = $faker->name();
            $surname = $faker->lastName() . " " . $faker->lastName();
            $country = $faker->country();
            $image = "img/authors/" . $faker->fakeImg(dir: __DIR__ . "/../../public/img/authors/", width: 640, height: 640, fullPath: false, text: strtoupper(substr($name, 0, 1) . substr($surname, 0, 1) . substr(explode(" ", $surname)[1], 0, 1)), backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]);
            (new Author)
                ->setName($name)
                ->setSurname($surname)
                ->setCountry($country)
                ->setImage($image)
                ->create();
        }
    }

    public static function restoreAuthors(): void
    {
        parent::executeQuery("DELETE FROM authors", "Failed to restore table authors");
        parent::executeQuery("ALTER TABLE authors AUTO_INCREMENT = 1", "Failed to restore auto increment of the table authors");
    }

    public static function getAllIds(): array
    {
        $ids = [];
        $result = parent::executeQuery("SELECT id FROM authors", "Failed retraiving IDs of authors");
        while ($row = $result->fetchColumn()) {
            $ids[] = (int)$row;
        }
        return $ids;
    }

    public static function  getImageById(int $id): string |false
    {
        return parent::executeQuery("SELECT image FROM authors WHERE id = :i", "Failed while retriving iamge of author with ID '$id'", [
            ":i" => $id,
        ])->fetchColumn();
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

    /**
     * Get the value of image
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
