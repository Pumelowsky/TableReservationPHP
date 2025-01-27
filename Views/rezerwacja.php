<?php
include '../public/layout/header.php';
//session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger' role='alert'>Musisz być zalogowany, aby zarezerwować stolik!</div>";
} else {
?>

    <div class="container py-5 mb-5">
        <h2 class="text-center mb-4">Formularz Rezerwacji</h2>
        <form id="reservation_form" action="/rezerwuj" method="POST">
            <div id="alert-container"></div>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="form-group">
                <label for="reservation_date">Data rezerwacji</label>
                <input type="date" id="reservation_date" name="reservation_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="table_id">Wybierz stolik</label>
                <select id="table_id" name="table_id" class="form-control" required>
                    <option value="">Wybierz stolik</option>
                </select>
            </div>

            <div class="form-group">
                <label for="guests">Liczba gości</label>
                <input type="number" id="guests" name="guests" class="form-control" required min="1">
            </div>

            <button type="submit" class="btn btn-primary">Zarezerwuj</button>
        </form>
        <input type="hidden" id="tables_data" value='[]'>
        <div id="available_tables" class="mt-4">
            <h4>Dostępne stoliki:</h4>
            <ul id="tables_list" class="list-group">
            </ul>
        </div>

        <h2>Twoje rezerwacje:</h2>

        <?php if (isset($userReservations) && count($userReservations) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Numer stolika</th>
                        <th>Data rezerwacji</th>
                        <th>Liczba gości</th>
                        <th>Status</th>
                        <th>Opcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userReservations as $reservation): ?>
                        <tr>
                            <td><?php echo $reservation['table_number']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($reservation['reservation_date'])); ?></td>
                            <td><?php echo $reservation['guests']; ?></td>
                            <td>
                                <?php
                                if ($reservation['cancelled']) {
                                    echo '<span class="badge bg-danger">Odwołana</span>';
                                } elseif ($reservation['reservation_date'] >= date('Y-m-d')) {
                                    echo '<span class="badge bg-primary">Oczekuje</span>';
                                } else {
                                    echo '<span class="badge bg-success">Zrealizowana</span>';
                                }
                                ?>
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
        <?php else: ?>
            <p>Nie masz żadnych rezerwacji.</p>
        <?php endif; ?>
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

        function fetchAvailableTables() {
            var reservationDate = document.getElementById("reservation_date").value;

            if (!reservationDate) {
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/available-tables?reservation_date=" + reservationDate, true);

            xhr.onload = function() {
                if (xhr.status == 200) {
                    try {
                        var tables = JSON.parse(xhr.responseText);

                        var tableSelect = document.getElementById("table_id");
                        tableSelect.innerHTML = "<option value=''>Wybierz stolik</option>";

                        var tablesList = document.getElementById("tables_list");
                        tablesList.innerHTML = "";

                        if (tables.length > 0) {
                            tables.forEach(function(table) {
                                var option = document.createElement("option");
                                option.value = table.id;
                                option.textContent = "Numer stolika: " + table.table_number + " | Możliwość pomieszczenia: " + table.seats + " gości";
                                tableSelect.appendChild(option);
                                var listItem = document.createElement("li");
                                listItem.className = "list-group-item";
                                listItem.textContent = "Stolik nr " + table.table_number + " | Miejsca: " + table.seats + " gości";
                                tablesList.appendChild(listItem);
                            });
                        } else {
                            var noTablesItem = document.createElement("li");
                            noTablesItem.className = "list-group-item";
                            noTablesItem.textContent = "Brak dostępnych stolików na tę datę.";
                            tablesList.appendChild(noTablesItem);
                        }
                        document.getElementById("tables_data").value = JSON.stringify(tables);
                    } catch (e) {
                        console.error("Błąd JSON:", e);
                    }
                } else {
                    console.error("Błąd podczas pobierania stolików:", xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error("Błąd zapytania.");
            };

            xhr.send();
        }

        function validateReservationForm() {
            var tableSelect = document.getElementById("table_id");
            var guestInput = document.getElementById("guests");
            var selectedTableId = tableSelect.value;
            var alertContainer = document.getElementById("alert-container");
            alertContainer.innerHTML = '';
            if (!selectedTableId) {
                showAlert("Proszę wybrać stolik.", "danger");
                return false;
            }

            var selectedTable = null;
            var tables = JSON.parse(document.getElementById("tables_data").value);

            for (var i = 0; i < tables.length; i++) {
                if (tables[i].id == selectedTableId) {
                    selectedTable = tables[i];
                    break;
                }
            }
            var guests = guestInput.value;
            if (guests > selectedTable.seats) {
                showAlert("Liczba gości nie może być większa niż dostępne miejsca przy stoliku.", "danger");
                return false;
            }

            return true;
        }

        function showAlert(message, type) {
            var alertContainer = document.getElementById("alert-container");
            var alert = document.createElement("div");
            alert.className = "alert alert-" + type + " alert-dismissible fade show";
            alert.role = "alert";
            alert.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            alertContainer.appendChild(alert);
        }

        document.getElementById("reservation_date").addEventListener("change", fetchAvailableTables);
        window.onload = fetchAvailableTables;
        document.getElementById("reservation_form").addEventListener("submit", function(event) {
            if (!validateReservationForm()) {
                event.preventDefault();
            }
        });
    </script>

<?php
}
include '../public/layout/footer.php';
?>