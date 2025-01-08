<?php
require_once '../models/Reservation.php';
require_once '../models/Table.php';
require_once '../config/db.php';

class ReservationController
{
    public function showReservationForm()
    {
        global $pdo;

        $reservation_date = $_POST['reservation_date'] ?? date('Y-m-d');
        $availableTables = Table::getAvailableTables($pdo, $reservation_date);

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $userReservations = Reservation::getUserReservations($pdo, $user_id);
        } else {
            $userReservations = [];
        }

        include '../views/rezerwacja.php';
    }


    public function getAvailableTables()
    {
        global $pdo;

        $reservation_date = $_GET['reservation_date'] ?? date('Y-m-d');


        $availableTables = Table::getAvailableTables($pdo, $reservation_date);
        $availableTables = array_filter($availableTables, function ($table) {
            return $table['cancelled'] == FALSE;
        });
        header('Content-Type: application/json');
        echo json_encode($availableTables);
        exit;
    }

    public function createReservation()
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                echo "Musisz być zalogowany!";
                exit;
            }

            $table_id = $_POST['table_id'];
            $guests = $_POST['guests'];
            $reservation_date = $_POST['reservation_date'];
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM reservations WHERE table_id = :table_id AND reservation_date = :reservation_date AND cancelled = FALSE";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':table_id', $table_id);
            $stmt->bindParam(':reservation_date', $reservation_date);
            $stmt->execute();
            $existingReservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingReservation) {
                echo "Stolik jest już zarezerwowany!";
            } else {
                $result = Reservation::createReservation($pdo, $table_id, $user_id, $guests, $reservation_date);

                if ($result) {
                    $_SESSION['success_message'] = "Rezerwacja została pomyślnie złożona na $reservation_date dla $guests gości!";
                    header('Location: /rezerwuj');
                } else {
                    echo "Błąd podczas tworzenia rezerwacji!";
                }
            }
        }
    }
    public function cancelReservation()
    {
        global $pdo;
        $data = json_decode(file_get_contents('php://input'), true);
        $reservation_id = $data['reservation_id'] ?? null;

        if (!$reservation_id) {
            echo json_encode(['success' => false, 'message' => 'Brak ID rezerwacji!']);
            return;
        }

        try {
            $query = "UPDATE reservations SET cancelled = 1 WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Rezerwacja anulowana!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nie znaleziono rezerwacji lub już została anulowana.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()]);
        }
    }
    public function restoreReservation()
    {
        global $pdo;
        $data = json_decode(file_get_contents('php://input'), true);
        $reservation_id = $data['reservation_id'] ?? null;

        if (!$reservation_id) {
            echo json_encode(['success' => false, 'message' => 'Brak ID rezerwacji!']);
            return;
        }

        try {
            $query = "UPDATE reservations SET cancelled = 0 WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Rezerwacja przywrócona!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nie znaleziono rezerwacji lub już została przywrócona.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()]);
        }
    }
}
