<?php
include './layout/header.php';
if ($_SESSION['role'] != "admin") {
    echo "Musisz być administratorem!";
    exit;
}
require_once '../controllers/StatystykiController.php';
$statystykiController = new StatystykiController();
$statystyki = $statystykiController->getStatistics();
$reservationsData = $statystyki['reservationsData'];
$cancelledData = $statystyki['cancelledData'];

?>
<div class="container mt-5">
    <h1>Statystyki Rezerwacji</h1>
    <form method="GET" class="mb-4">
        <div class="row">
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
    <div class="row">
        <div class="col-md-6">
            <h3>Liczba Rezerwacji w Okresie</h3>
            <canvas id="reservationsChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Liczba Anulowanych Rezerwacji w Okresie</h3>
            <canvas id="cancelledChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var reservationsData = <?= json_encode($reservationsData) ?>;
    var cancelledData = <?= json_encode($cancelledData) ?>;
    var ctx1 = document.getElementById('reservationsChart').getContext('2d');
    var reservationsChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: reservationsData.labels,
            datasets: [{
                label: 'Liczba Rezerwacji',
                data: reservationsData.values,
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    var ctx2 = document.getElementById('cancelledChart').getContext('2d');
    var cancelledChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: cancelledData.labels,
            datasets: [{
                label: 'Liczba Anulowanych Rezerwacji',
                data: cancelledData.values,
                backgroundColor: 'rgba(220, 53, 69, 0.5)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include './layout/footer.php'; ?>