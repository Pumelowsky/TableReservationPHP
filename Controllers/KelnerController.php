<?php
require_once '../models/Reservation.php';
require_once '../config/db.php';

class KelnerController
{
    //Wyświetlanie widoku panelu kelnera z filtrami
    public function showKelnerPanel()
    {
        global $pdo;

        $filters = [
            'status' => $_GET['status'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
        ];
        $reservations = Reservation::getReservationsWithFilters($pdo, $filters);

        include '../views/kelner.php';
    }

    //Odwoływanie rezerwachu
    public function cancelReservation()
    {
        global $pdo;

        $reservation_id = $_POST['reservation_id'] ?? null;

        if (!$reservation_id) {
            echo json_encode(['success' => false, 'message' => 'Brak ID rezerwacji!']);
            return;
        }

        $query = "UPDATE reservations SET cancelled = 1 WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Rezerwacja została anulowana.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nie udało się anulować rezerwacji.']);
        }
    }
}
