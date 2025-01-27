<?php
// models/User.php

class User
{
    //Sprawdzenie czy dany użytkownik istnieje już w bazie
    //Do walidacji rejestracji
    public static function checkIfUserExists($pdo, $username, $email)
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    //Obsługa do bazy rejestracji użytkowników
    public static function register($pdo, $username, $email, $password)
    {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    //Obsługa do bazy logowania użytkowników
    public static function login($pdo, $username, $password)
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
