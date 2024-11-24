<?php

namespace App\database;

use Exception;
use PDOException;
use PDOStatement;

class QueryExecutor extends Connection
{
    protected static function executeQuery(string $query, ?string $customErrorMessage = null, ?array $parametersStatament = null): PDOStatement
    {
        $connection = parent::getInstance();
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
