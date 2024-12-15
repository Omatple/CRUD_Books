<?php

namespace App\Database;

use Exception;
use PDOException;
use PDOStatement;

require __DIR__ . "/../../vendor/autoload.php";
class QueryExecutor
{
    protected static function executeQuery(string $query, ?string $customErrorMessage = null, ?array $parametersStatament = null): PDOStatement
    {
        $connection = Connection::getInstance();
        $pdo = $connection->getConnection()->prepare($query);
        try {
            ($parametersStatament === null) ?
                $pdo->execute()
                :
                $pdo->execute($parametersStatament);
            return $pdo;
        } catch (PDOException $e) {
            $errorMessage = ($customErrorMessage === null) ?
                "Failed executing query: {$e->getMessage()}"
                :
                "$customErrorMessage: {$e->getMessage()}";
            throw new Exception($errorMessage, (int) $e->getCode());
        } finally {
            $connection->closeConnection();
        }
    }
}
