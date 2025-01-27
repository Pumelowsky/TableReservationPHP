<?php
require_once '../config/db.php';
require_once '../models/User.php';

class AuthController
{
    //Funkcja rejestracji
    public function register()
    {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if (User::checkIfUserExists($pdo, $username, $email)) {
                $_SESSION['status_message'] = "Użytkownik o tej nazwie lub emailu już istnieje!";
                header('Location: /register');
                exit();
            }

            $result = User::register($pdo, $username, $email, $password);

            if ($result) {
                $_SESSION['status_message'] = "Rejestracja zakończona sukcesem! Możesz się teraz zalogować.";
                header('Location: /login');
            } else {
                $_SESSION['status_message'] = "Błąd podczas rejestracji!";
                header('Location: /register');
            }
        }
    }

    //Funkcja logowania
    public function login()
    {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = User::login($pdo, $username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                header('Location: /');
            } else {
                $_SESSION['status_message'] = "Błędny login lub hasło!";
                header('Location: /');
            }
        }
    }
    //Funkcja wylogowania
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }
}
