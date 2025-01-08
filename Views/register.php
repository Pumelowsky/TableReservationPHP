<?php
include './layout/header.php';
$statusMessage = '';
if (isset($_SESSION['status_message'])) {
    $statusMessage = $_SESSION['status_message'];
    unset($_SESSION['status_message']);
}
?>

<div class="container">
    <h2 class="my-4">Rejestracja</h2>

    <?php if ($statusMessage): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $statusMessage; ?>
        </div>
    <?php endif; ?>

    <form action="/registerSubmit" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nazwa użytkownika</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Hasło</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Zarejestruj się</button>
    </form>
</div>
<?php include './layout/footer.php'; ?>