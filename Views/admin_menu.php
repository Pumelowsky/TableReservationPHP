<?php
include './layout/header.php';
if ($_SESSION['role'] != "admin") {
    echo "Musisz być administratorem!";
    exit;
}
?>
<div class="container mt-5">
    <h1>Zarządzanie Menu</h1>
    <a href="/admin/menu/add" class="btn btn-primary">Dodaj Nowe Danie</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menuItems as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td><?php echo $item['price']; ?> zł</td>
                    <td>
                        <a href="/admin/menu/edit?id=<?php echo $item['id']; ?>" class="btn btn-warning">Edytuj</a>
                        <a href="/admin/menu/delete?id=<?php echo $item['id']; ?>" class="btn btn-danger">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include './layout/footer.php'; ?>