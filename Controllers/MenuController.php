<?php
require_once '../models/Menu.php';
require_once '../config/db.php';

class MenuController
{
    //Wyświetlanie widoku Menu
    public function showMenu()
    {
        global $pdo;
        $menuItems = Menu::getAllMenuItems($pdo);
        $userId = $_SESSION['user_id'];
        $favoriteItems = Menu::getUserFavorite($pdo);
        //Sprawdzenie czy potrawa jest dodana do ulubionych
        $favoriteIds = array_column(
            array_filter($favoriteItems, function ($fav) use ($userId) {
                return $fav['user_id'] == $userId;
            }),
            'menu_id'
        );
        //Dodajemy kolumne is_favorite
        foreach ($menuItems as &$item) {
            $item['is_favorite'] = in_array($item['id'], $favoriteIds);
        }
        unset($item);
        include './layout/header.php';
        include '../views/menu.php';
        include './layout/footer.php';
    }
    //Obsługa dodawania i usuwania z ulubionych
    public function actionMenu()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'];
            $menuId = $_POST['menu_id'];
            $userId = $_SESSION['user_id'];

            if(!$userId) {
                header('Location: /menu');
                return;
            }
            if ($action === 'add') {
                Menu::addItemToFavorite($pdo, $userId, $menuId);
                header('Location: /menu');
            } elseif ($action === 'remove') {
                Menu::removeItemFromFavorite($pdo, $userId, $menuId);
                header('Location: /menu');
            }
        }
    }
}
