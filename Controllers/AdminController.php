<?php
// Controllers/AdminController.php
require_once '../models/Reservation.php';
require_once '../models/Table.php';
require_once '../models/Menu.php';
require_once '../models/Statystyki.php';
require_once '../config/db.php';

class AdminController
{
    public function showAdminDashboard()
    {
        include '../views/admin_dash.php';
    }

    public function manageReservations()
    {
        global $pdo;
        $filters = [
            'status' => $_GET['status'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
        ];
        $reservations = Reservation::getReservationsWithFilters($pdo, $filters);

        include '../views/admin_reservation.php';
    }

    public function manageMenu()
    {
        global $pdo;
        $menuItems = Menu::getAllMenuItems($pdo);
        include '../views/admin_menu.php';
    }
    public function addMenuItem()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            if ($_SESSION['role'] != "admin") {
                echo "Musisz być administratorem!";
                exit;
            }
            $result = Menu::addMenuItem($pdo, $name, $description, $price);

            if ($result) {
                header('Location: /admin/menu');
            } else {
                echo "Błąd podczas dodawania dania!";
            }
        }

        include '../views/admin_add_menu_item.php';
    }

    public function editMenuItem()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            if ($_SESSION['role'] != "admin") {
                echo "Musisz być administratorem!";
                exit;
            }
            $result = Menu::editMenuItem($pdo, $id, $name, $description, $price);

            if ($result) {
                header('Location: /admin/menu');
            } else {
                echo "Błąd podczas edytowania dania!";
            }
        } else {
            $id = $_GET['id'];
            $menuItem = Menu::getMenuItemById($pdo, $id);
            include '../views/admin_edit_menu_item.php';
        }
    }

    public function deleteMenuItem()
    {
        global $pdo;
        $id = $_GET['id'];
        if ($_SESSION['role'] != "admin") {
            echo "Musisz być administratorem!";
            exit;
        }
        $result = Menu::deleteMenuItem($pdo, $id);

        if ($result) {
            header('Location: /admin/menu');
        } else {
            echo "Błąd podczas usuwania dania!";
        }
    }
    public function showStatistics()
    {
        global $pdo;
        $statistics = Statystyki::getReservationStatistics($pdo);
        include '../views/admin_statistics.php';
    }
}
