<?php

namespace App\Database;

use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

class AuthorRepository extends QueryExecutor
{
    private int $authorId;
    private string $firstName;
    private string $lastName;
    private string $originCountry;
    private string $profileImage;

    public static function fetchAll(): array
    {
        return parent::executeQuery("SELECT * FROM authors", "Error fetching authors list")->fetchAll();
    }

    public function save(): void
    {
        $parameters = [
            ":firstName" => $this->firstName,
            ":lastName" => $this->lastName,
            ":country" => $this->originCountry,
            ":image" => $this->profileImage,
        ];
        parent::executeQuery(
            "INSERT INTO authors (name, surname, country, image) VALUES (:firstName, :lastName, :country, :image)",
            "Error adding new author '{$this->firstName}'",
            $parameters
        );
    }

    public static function removeById(int $authorId): void
    {
        parent::executeQuery("DELETE FROM authors WHERE id = :id", "Error deleting author with ID '$authorId'", [":id" => $authorId]);
    }

    public static function isUniqueField(string $columnName, string $fieldValue, ?int $excludedId = null): bool
    {
        $parameters = $excludedId === null ? 
            [":field" => $fieldValue] : 
            [":field" => $fieldValue, ":excludedId" => $excludedId];

        $query = $excludedId === null ? 
            "SELECT * FROM authors WHERE $columnName = :field" : 
            "SELECT * FROM authors WHERE $columnName = :field AND id <> :excludedId";

        return !parent::executeQuery(
            $query,
            "Error checking uniqueness of '$columnName' with value '$fieldValue'",
            $parameters
        )->fetchColumn();
    }

    public function updateById(int $authorId): void
    {
        $parameters = [
            ":id" => $authorId,
            ":firstName" => $this->firstName,
            ":lastName" => $this->lastName,
            ":country" => $this->originCountry,
            ":image" => $this->profileImage,
        ];
        parent::executeQuery(
            "UPDATE authors SET name = :firstName, surname = :lastName, country = :country, image = :image WHERE id = :id",
            "Error updating author with ID '$authorId'",
            $parameters
        );
    }

    public static function isUniqueFullName(string $firstName, string $lastName, ?int $excludedId = null): bool
    {
        $queryCondition = is_null($excludedId) ? "" : " AND id <> :excludedId";
        $parameters = is_null($excludedId) ? [
            ":firstName" => $firstName,
            ":lastName" => $lastName,
        ] : [
            ":firstName" => $firstName,
            ":lastName" => $lastName,
            ":excludedId" => $excludedId,
        ];

        return !parent::executeQuery(
            "SELECT * FROM authors WHERE name = :firstName AND surname = :lastName$queryCondition",
            "Error checking uniqueness of full name",
            $parameters
        )->fetchColumn();
    }

    public static function fetchById(int $authorId): array|false
    {
        return parent::executeQuery(
            "SELECT * FROM authors WHERE id = :id", 
            "Error fetching author by ID", 
            [":id" => $authorId]
        )->fetch();
    }

    public static function generateFakeAuthors(): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        $fakeAuthorCount = 20;

        for ($i = 0; $i < $fakeAuthorCount; $i++) {
            $firstName = $faker->name();
            $lastName = $faker->lastName() . " " . $faker->lastName();
            $country = $faker->country();
            $profileImage = "img/authors/" . $faker->fakeImg(
                dir: __DIR__ . "/../../public/authors/img",
                width: 640,
                height: 640,
                fullPath: false,
                text: strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1) . substr(explode(" ", $lastName)[1], 0, 1)),
                backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]
            );
            (new AuthorRepository)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setOriginCountry($country)
                ->setProfileImage($profileImage)
                ->save();
        }
    }

    public static function resetAuthors(): void
    {
        parent::executeQuery("DELETE FROM authors", "Error clearing authors table");
        parent::executeQuery("ALTER TABLE authors AUTO_INCREMENT = 1", "Error resetting auto increment");
    }

    public static function fetchAllIds(): array
    {
        $authorIds = [];
        $result = parent::executeQuery("SELECT id FROM authors", "Error fetching author IDs");
        while ($row = $result->fetchColumn()) {
            $authorIds[] = (int)$row;
        }
        return $authorIds;
    }

    public static function fetchImageById(int $authorId): string|false
    {
        return parent::executeQuery(
            "SELECT image FROM authors WHERE id = :id", 
            "Error fetching image for author ID '$authorId'", 
            [":id" => $authorId]
        )->fetchColumn();
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

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getOriginCountry()
    {
        return $this->originCountry;
    }

    public function setOriginCountry($originCountry)
    {
        $this->originCountry = $originCountry;
        return $this;
    }

    public function getProfileImage(): string
    {
        return $this->profileImage;
    }

    public function setProfileImage(string $profileImage): self
    {
        $this->profileImage = $profileImage;
        return $this;
    }
}
