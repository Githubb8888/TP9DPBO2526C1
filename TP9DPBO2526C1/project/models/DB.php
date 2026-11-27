<?php

class DB
{
    private PDO $pdo;
    private ?PDOStatement $stmt = null;

    public function __construct(string $host, string $dbName, string $user, string $pass)
    {
        $this->pdo = new PDO(
            'mysql:host=' . $host . ';dbname=' . $dbName . ';charset=utf8mb4',
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    protected function executeQuery(string $query, array $params = []): bool
    {
        $this->stmt = $this->pdo->prepare($query);
        return $this->stmt->execute($params);
    }

    protected function getAllResult(): array
    {
        if ($this->stmt === null) {
            return [];
        }
        return $this->stmt->fetchAll();
    }
}
