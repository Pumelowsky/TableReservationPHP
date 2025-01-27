<?php
include './layout/header.php';
if ($_SESSION['role'] != "admin") { //Czy jest administratorem
    echo "Musisz być administratorem!";
    exit;
}
?>
<div class="container mt-5">
    <h1>Edytuj Danie</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $menuItem['id']; ?>">
        <div class="form-group">
            <label for="name">Nazwa</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $menuItem['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Opis</label>
            <textarea name="description" id="description" class="form-control" required><?php echo $menuItem['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Cena</label>
            <input type="number" name="price" id="price" class="form-control" value="<?php echo $menuItem['price']; ?>" required step="0.01">
        </div>
        <button type="submit" class="btn btn-warning">Zapisz Zmiany</button>
    </form>
</div>

<?php include './layout/footer.php'; ?>