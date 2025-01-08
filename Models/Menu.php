<?php

class Menu
{
    public static function getAllMenuItems($pdo)
    {
        $query = "SELECT id, name, description, price FROM menu";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function addMenuItem($pdo, $name, $description, $price)
    {
        $query = "INSERT INTO menu (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }
    public static function editMenuItem($pdo, $id, $name, $description, $price)
    {
        $query = "UPDATE menu SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }
    public static function deleteMenuItem($pdo, $id)
    {
        $query = "DELETE FROM menu WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public static function getMenuItemById($pdo, $id)
    {
        $query = "SELECT * FROM menu WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
