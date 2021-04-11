<?php

namespace App\Model;

class AgencyManager extends AbstractManager
{
    public const TABLE = 'agency';

    /**
     * Insert new item in database
     */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`,`address`) VALUES (:name, :address)");
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('address', $item['address'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name, `address` = :address WHERE id_agency=:id");
        $statement->bindValue('id_agency', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $item['name'], \PDO::PARAM_STR);
        $statement->bindValue('address', $item['address'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
