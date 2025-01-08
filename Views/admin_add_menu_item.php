<?php
include './layout/header.php';
if ($_SESSION['role'] != "admin") {
    echo "Musisz być administratorem!";
    exit;
}
?>
<div class="container mt-5">
    <h1>Dodaj Nowe Danie</h1>
    <form method="POST">
        <div class="form-group">
            <label for="name">Nazwa</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Opis</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Cena</label>
            <input type="number" name="price" id="price" class="form-control" required step="0.01">
        </div>
        <button type="submit" class="btn btn-success">Dodaj</button>
    </form>
</div>
<?php include './layout/footer.php'; ?>