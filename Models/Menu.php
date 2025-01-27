<?php

class Menu
{
    //Pobieranie wszystkich elementów menu
    public static function getAllMenuItems($pdo)
    {
        $query = "SELECT id, name, description, price FROM menu";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Pobieranie wszystkich elementów ulubionych
    public static function getUserFavorite($pdo)
    {
        $query = "SELECT user_id, menu_id FROM favorite";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Funkcja do walidacji czy dana potrawa nie jest już dodana jako ulubiona dla danego użytkownika
    public static function isAlreadyFavorite($pdo, $user, $menuItem)
    {
        $query = "SELECT user_id, menu_id FROM favorite WHERE user_id = :user_id AND menu_id = :menu_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user);
        $stmt->bindParam(':menu_id', $menuItem);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    //Dodawanie elementu do ulubionych
    public static function addItemToFavorite($pdo, $user, $menuItem)
    {
        if(Menu::isAlreadyFavorite($pdo, $user, $menuItem)) return;
        $query = "INSERT INTO favorite (user_id, menu_id) VALUES (:user_id, :menu_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user);
        $stmt->bindParam(':menu_id', $menuItem);
        return $stmt->execute();
    }
    //Usuwanie elementu z ulubionych
    public static function removeItemFromFavorite($pdo, $user, $menuItem)
    {
        $query = "DELETE FROM favorite WHERE user_id = :user_id AND menu_id = :menu_id ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user);
        $stmt->bindParam(':menu_id', $menuItem);
        return $stmt->execute();
    }
    //Dodawanie elementu do menu
    public static function addMenuItem($pdo, $name, $description, $price)
    {
        $query = "INSERT INTO menu (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }
    //Edytowanie elementu menu
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
    //Usuwanie elementu z menu
    public static function deleteMenuItem($pdo, $id)
    {
        $query = "DELETE FROM menu WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    //Pobieranie elementu z menu po jego id
    public static function getMenuItemById($pdo, $id)
    {
        $query = "SELECT * FROM menu WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
