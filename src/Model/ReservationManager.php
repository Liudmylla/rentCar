<?php

namespace App\Model;

class ReservationManager extends AbstractManager
{
    public const TABLE1 = 'rent';
    public const TABLE2 = 'agency';
    public const TABLE3 = 'vehicle';
    public const TABLE4 = 'category';

    /**
     * Get one row from database by ID.
     *
     */
    public function selectOneVehicle(int $id)
    {
        $query = 'SELECT * FROM ' . self::TABLE3 . ' AS ve ';
        $query .= 'JOIN ' . self::TABLE4 . ' AS ca ON ca.id=ve.category_id ';
        $query .= 'JOIN ' . self::TABLE2 . ' AS ag ON ag.id=ve.agency_id ';
        $query .= 'WHERE ve.id = ' . $id;

        return $this->pdo->query($query)->fetch();
    }
    /**
     * Get all agency vehicles from database.
     */
    public function selectAgencyVehicles(int $agencyId, int $categoryId, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE3 . ' AS ve ';
        $query .= 'JOIN ' . self::TABLE4 . ' AS ca ON ca.id=ve.category_id ';
        $query .= 'JOIN ' . self::TABLE2 . ' AS ag ON ag.id=ve.agency_id ';
        $query .= 'WHERE ve.agency_id = ' . $agencyId;
        $query .= ' AND ve.category_id = ' . $categoryId;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
    
    /**
     * Get all the rented history for one vehicle from database.
     */
    public function selectRentedHistory(int $vehicleId, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE1 ;
        $query .= ' WHERE vehicle_id = ' . $vehicleId;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
    
    /**
     * Insert new item in database
     */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE1
         . " (`user_id`,`vehicle_id`, `reduction_percent`,`date_creation`, `date_start`, `date_end`, `total_amount`) 
        VALUES (:userId, :vehicleId, :reduction, :dateCreation, :dateStart, :dateEnd, :totalAmount)");
        $statement->bindValue('userId', $item['userId'], \PDO::PARAM_INT);
        $statement->bindValue('vehicleId', $item['vehicleId'], \PDO::PARAM_INT);
        $statement->bindValue('reduction', $item['reduction'], \PDO::PARAM_INT);
        $statement->bindValue('dateCreation', $item['dateCreation'], \PDO::PARAM_STR);
        $statement->bindValue('dateStart', $item['dateStart'], \PDO::PARAM_STR);
        $statement->bindValue('dateEnd', $item['dateEnd'], \PDO::PARAM_STR);
        $statement->bindValue('totalAmount', $item['totalAmount'], \PDO::PARAM_INT);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        //to modify
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE1 . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('user_id', $item['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('vehicle_id', $item['agency_id'], \PDO::PARAM_INT);
        $statement->bindValue('date_creation', $item['brand'], \PDO::PARAM_STR);
        $statement->bindValue('date_start', $item['model'], \PDO::PARAM_STR);
        $statement->bindValue('date_end', $item['image'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
