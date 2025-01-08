<?php
include './layout/header.php';
if ($_SESSION['role'] != "admin") {
    echo "Musisz być administratorem!";
    exit;
}
?>
<div class="container p-5">
    <h1>Panel Administratora</h1>
    <div class="list-group">
        <a href="/admin/reservations" class="list-group-item list-group-item-action">Zarządzanie Rezerwacjami</a>
        <a href="/admin/menu" class="list-group-item list-group-item-action">Zarządzanie Menu</a>
        <a href="/admin/statistics" class="list-group-item list-group-item-action">Statystyki Rezerwacji</a>
    </div>
</div>
<?php include './layout/footer.php'; ?>