<?php include './layout/header.php';

if ($_SESSION['role'] != "kelner") { //Czy jest kelnerem
    echo "Musisz być kelnerem!";
    exit;
}
?>
<div class="container mt-5">
    <h1 class="mb-4">Panel Kelnera</h1>
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Wszystkie</option>
                    <option value="active" <?= ($_GET['status'] ?? '') == 'active' ? 'selected' : '' ?>>Aktywne</option>
                    <option value="cancelled" <?= ($_GET['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Anulowane</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from">Od daty</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="<?= $_GET['date_from'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label for="date_to">Do daty</label>
                <input type="date" id="date_to" name="date_to" class="form-control" value="<?= $_GET['date_to'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filtruj</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numer stolika</th>
                <th>Użytkownik</th>
                <th>Data rezerwacji</th>
                <th>Liczba gości</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= $reservation['id'] ?></td>
                    <td><?= $reservation['table_number'] ?></td>
                    <td><?= $reservation['username'] ?></td>
                    <td><?= $reservation['reservation_date'] ?></td>
                    <td><?= $reservation['guests'] ?></td>
                    <td>
                        <?php if ($reservation['cancelled']): ?>
                            <span class="badge" style="background-color: #dc3545; color: white;">Anulowana</span>
                        <?php else: ?>
                            <span class="badge" style="background-color: #28a745; color: white;">Aktywna</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$reservation['cancelled']): ?>
                            <button class="btn btn-danger btn-sm cancel-reservation" data-reservation-id="<?php echo $reservation['id']; ?>" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                Odwołaj
                            </button>
                        <?php else: ?>
                            <span class="text-muted">Brak akcji</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Czy na pewno?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Czy na pewno chcesz odwołać tę rezerwację?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-danger" id="confirm-cancel">Tak, odwołaj</button>
            </div>
        </div>
    </div>
</div>
<script>
    let reservationIdToCancel = null;

    document.addEventListener("click", function(e) {
        if (e.target && e.target.classList.contains("cancel-reservation")) {
            reservationIdToCancel = e.target.dataset.reservationId;
        }
    });

    document.getElementById("confirm-cancel").addEventListener("click", function() {
        if (!reservationIdToCancel) {
            console.error("Brak ID rezerwacji!");
            alert("Nie można anulować rezerwacji: Brak ID.");
            return;
        }

        fetch(`/cancel-reservation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    reservation_id: reservationIdToCancel
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Rezerwacja została anulowana!");
                    location.reload();
                } else {
                    alert("Błąd anulowania rezerwacji: " + data.message);
                }
            })
            .catch(error => {
                console.error("Błąd w żądaniu AJAX:", error);
            });

        var cancelModal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
        cancelModal.hide();
    });
</script>
<?php include './layout/footer.php'; ?>