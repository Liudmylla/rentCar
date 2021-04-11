<?php

namespace App\Model;

class VehicleManager extends AbstractManager
{
    public const TABLE = 'vehicle';

    /**
     * Insert new item in database
     */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`category_id`,`agency_id`, `brand`, `model`, `image`, `color`, `description`, `gear_box`,`energy` ) 
                                                                    VALUES (:category_id, :agency_id, :brand, :model, :image, :color, :description, :gear_box, :energy)");
        $statement->bindValue('category_id', $item['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('agency_id', $item['agency_id'], \PDO::PARAM_INT);
        $statement->bindValue('brand', $item['brand'], \PDO::PARAM_STR);
        $statement->bindValue('model', $item['model'], \PDO::PARAM_STR);
        $statement->bindValue('image', $item['image'], \PDO::PARAM_STR);
        $statement->bindValue('color', $item['color'], \PDO::PARAM_STR);
        $statement->bindValue('description', $item['description'], \PDO::PARAM_STR);
        $statement->bindValue('gear_box', $item['gear_box'], \PDO::PARAM_STR);
        $statement->bindValue('energy', $item['energy'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        //to modify
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET `category_id` = :category_id, 
            `agency_id` = :agency_id, 
            `brand` = :brand, 
            `model` = :model, 
            `image` = :image, 
            `color` = :color, 
            `description` = :description, 
            `gear_box` = :gear_box, 
            `energy` = :energy

        WHERE id_vehicle=:id_vehicle");
        $statement->bindValue('id_vehicle', $item['id_vehicle'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $item['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('agency_id', $item['agency_id'], \PDO::PARAM_INT);
        $statement->bindValue('brand', $item['brand'], \PDO::PARAM_STR);
        $statement->bindValue('model', $item['model'], \PDO::PARAM_STR);
        $statement->bindValue('image', $item['image'], \PDO::PARAM_STR);
        $statement->bindValue('color', $item['color'], \PDO::PARAM_STR);
        $statement->bindValue('description', $item['description'], \PDO::PARAM_STR);
        $statement->bindValue('gear_box', $item['gear_box'], \PDO::PARAM_STR);
        $statement->bindValue('energy', $item['energy'], \PDO::PARAM_STR);
        return $statement->execute();
    }
    public function selectOneVehicleById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE id_vehicle =$id");
        $statement->bindValue('id_vehicle', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
    public function deleteVehicle(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . static::TABLE . " WHERE id_vehicle=$id");
        $statement->bindValue('id_vehicle', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
