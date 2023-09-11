<?php

namespace classes;

class DB
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function insert(string $sql, array $params): bool
    {
        $stm = $this->pdo->prepare($sql);

        return $stm->execute($params);
    }

    public function fetchColumn(string $sql, array $params): mixed
    {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);

        return $stm->fetchColumn();
    }
}
