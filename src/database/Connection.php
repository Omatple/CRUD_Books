<?php

namespace App\database;

use \Dotenv\Dotenv;
use \Exception;
use \PDO;
use \PDOException;
use \RuntimeException;

require __DIR__ . "/../../vendor/autoload.php";

class Connection
{
    private static ?Connection $instance = null;
    private ?PDO $connection = null;

    private function __construct()
    {
        $this->initializeInstance();
    }

    private function initializeInstance(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();
        foreach (["USER", "PASSWORD", "HOST", "PORT", "DBNAME"] as $key) {
            if (!isset($_ENV[$key])) throw new Exception("Error processing enviroment variables '$key'");
        }
        $dsn = "mysql:dbname={$_ENV['DBNAME']};host={$_ENV['HOST']};port={$_ENV['PORT']};charshet=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $_ENV['USER'], $_ENV['PASSWORD'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException("Error connection database. {$e->getMessage()}", (int) $e->getCode());
        }
    }

    protected static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new Connection;
        }
        return self::$instance;
    }

    protected function closeConnection(): void
    {
        $this->connection = null;
        self::$instance = null;
    }

    protected function getConnection(): PDO
    {
        return $this->connection;
    }
}
