<?php

//W tym pliku zawarty jest Routing projektu

session_start();


require_once '../config/db.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/ReservationController.php';
require_once '../controllers/KelnerController.php';
require_once '../controllers/AdminController.php';
require_once '../controllers/MenuController.php';

$authController = new AuthController();
$reservationController = new ReservationController();
$kelnerController = new KelnerController();
$adminController = new AdminController();
$menuController = new MenuController();
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$uri = str_replace('/public', '', $uri);

if ($uri == '/loginSubmit' && $method == 'POST') {
    $authController->login();
} elseif ($uri == '/registerSubmit' && $method == 'POST') {
    $authController->register();
} elseif ($uri == '/login' && $method == 'GET') {
    include '../views/login.php';
} elseif ($uri == '/register' && $method == 'GET') {
    include '../views/register.php';
} elseif ($uri == '/logout') {
    $authController->logout();
} elseif ($uri == '/kontakt' && $method == 'GET') {
    include '../views/contact.php';
} elseif ($uri == '/menu' && $method == 'GET') {
    $menuController->showMenu();
} elseif ($uri == '/menu' && $method == 'POST') {
    $menuController->actionMenu();
} elseif ($uri == '/rezerwuj' && $method == 'GET') {
    $reservationController->showReservationForm();
} elseif (strpos($uri, '/available-tables') !== false && $method == 'GET') {
    if (isset($_GET['reservation_date'])) {
        $reservationController->getAvailableTables();
    } else {
        echo "Brak daty rezerwacji w zapytaniu.";
    }
} elseif ($uri == '/rezerwuj' && $method == 'POST') {
    $reservationController->createReservation();
} elseif (strpos($uri, '/cancel-reservation') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationController->cancelReservation();
    exit;
} elseif (strpos($uri, '/restore-reservation') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationController->restoreReservation();
    exit;
} elseif ($_SERVER['REQUEST_URI'] == '/kelner') {
    $kelnerController->showKelnerPanel();
} elseif (strpos($uri, '/kelner') !== false && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $kelnerController->showKelnerPanel();
} elseif ($_SERVER['REQUEST_URI'] == '/admin' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->showAdminDashboard();
} elseif (strpos($uri, '/admin/reservations') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->manageReservations();
} elseif ($_SERVER['REQUEST_URI'] == '/admin/menu' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->manageMenu();
} elseif (strpos($uri, '/admin/menu/add') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->addMenuItem();
} elseif (strpos($uri, '/admin/menu/add') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminController->addMenuItem();
} elseif (strpos($uri, '/admin/menu/edit') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->editMenuItem();
} elseif (strpos($uri, '/admin/menu/edit') !== false && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminController->editMenuItem();
} elseif (strpos($uri, '/admin/menu/delete') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->deleteMenuItem();
} elseif (strpos($uri, '/admin/statistics') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $adminController->showStatistics();
} else {
    include '../views/index.php';
}
