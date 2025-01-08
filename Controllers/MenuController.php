<?php
require_once '../models/Menu.php';
require_once '../config/db.php';

class MenuController
{
    public function showMenu()
    {
        global $pdo;
        $menuItems = Menu::getAllMenuItems($pdo);
        include './layout/header.php';
        include '../views/menu.php';
        include './layout/footer.php';
    }
}

$controller = new MenuController();
$controller->showMenu();
