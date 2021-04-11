<?php

namespace App\Model;

use DateTime;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new item in database
     */
    public function insert(array $item)
    {
        //default role is user
        $role = 'user';
        $currentDate = new DateTime('now');
        $formatDate = $currentDate->format('Y-m-d');

        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`firstname`,`lastname`, `birthdate`, `address`, `role`, `creation_date`, `email`, `password`) 
        VALUES (:firstname, :lastname, :birthdate, :address, :role, :creation_date, :email, :password)");

        $statement->bindValue('firstname', $item['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $item['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthdate', $item['birthdate'], \PDO::PARAM_STR);
        $statement->bindValue('address', $item['address'], \PDO::PARAM_STR);
        $statement->bindValue('role', $role, \PDO::PARAM_STR);
        $statement->bindValue('creation_date', $formatDate, \PDO::PARAM_STR);
        $statement->bindValue('email', $item['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $item['password'], \PDO::PARAM_STR);
    

        $statement->execute();
      
     
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        // to modify
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $item['firstname'], \PDO::PARAM_INT);
        $statement->bindValue('lastname', $item['lastname'], \PDO::PARAM_INT);
        $statement->bindValue('birthdate', $item['birthdate'], \PDO::PARAM_STR);
        $statement->bindValue('address', $item['address'], \PDO::PARAM_STR);
        $statement->bindValue('role', $item['role'], \PDO::PARAM_STR);
        $statement->bindValue('creation_date', $item['creation_date'], \PDO::PARAM_STR);
        $statement->bindValue('email', $item['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $item['password'], \PDO::PARAM_STR);
        return $statement->execute();
    }


    // laurence : this function verifies if exists a user in user table
    // from his email
    // returns id pour commencer, and password if user is found

    public function selectOneByEmail(string $email, string $password)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE email=:email AND password=:password");
        //$regex = "/^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+.[a-z]{2,}$/i";
        //$statement->bindValue('email', $regex , \PDO::PARAM_STR);
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->bindValue('password', $password, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
