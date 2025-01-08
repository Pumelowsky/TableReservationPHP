<?php
include './layout/header.php';
$statusMessage = '';
if (isset($_SESSION['status_message'])) {
    $statusMessage = $_SESSION['status_message'];
    unset($_SESSION['status_message']);
}
?>
<div class="container-fluid my-5 login-section">
    <div class="col-3">
        <h2 class="my-4">Logowanie</h2>
        <?php if ($statusMessage): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $statusMessage; ?>
            </div>
        <?php endif; ?>
        <form action="/loginSubmit" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nazwa użytkownika</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Hasło</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Zaloguj się</button>
        </form>
    </div>
</div>
<?php
include './layout/footer.php';
?>