<?php
require_once '../models/Reservation.php';
require_once '../Controllers/EmailController.php';
require_once '../models/Table.php';
require_once '../config/db.php';

class ReservationController
{
    //Wyświetlanie formularza rezerwacji i historii rezerwacji użytkownika
    public function showReservationForm()
    {
        global $pdo;

        $reservation_date = $_POST['reservation_date'] ?? date('Y-m-d');
        $availableTables = Table::getAvailableTables($pdo, $reservation_date); // Dostępne stoły w danym terminie

        //Pobieranie rezerwacji użytkownika
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $userReservations = Reservation::getUserReservations($pdo, $user_id);
        } else {
            $userReservations = [];
        }

        include '../views/rezerwacja.php';
    }

    //API: /available-tables
    //Zwraca w formie JSON dane stolików które są dostępne w danym terminie
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

    //Tworzenie rezerwacji
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
            $user_email = $_SESSION['email'];
            $now = date('Y-m-d');
            $query = "SELECT * FROM reservations WHERE table_id = :table_id AND reservation_date = :reservation_date AND cancelled = FALSE";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':table_id', $table_id);
            $stmt->bindParam(':reservation_date', $reservation_date);
            $stmt->execute();
            $existingReservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingReservation) { //Sprawdzanie czy stolik nie jest już zarezerwowany
                echo "Stolik jest już zarezerwowany!";
            } else {
                if ($reservation_date < $now) // Czy data nie jest z przeszłości
                {
                    $_SESSION['error_message'] = "Rezerwacja nie może być wcześniej niż dzisiaj!";
                    header('Location: /rezerwuj');
                    return;
                }
                $result = Reservation::createReservation($pdo, $table_id, $user_id, $guests, $reservation_date);
            

                if ($result) {
                    //Wysyłanie maila
                    $emailController = new EmailController();
                    $emailController->send_email($user_email, 'Rezerwacja stolika', 
                    "<h3>Dziękujemy za rezerwacje.</h3><p>Potwierdzamy twoją rejestrację na $reservation_date dla $guests gości!</p>"
                    );
                    $_SESSION['success_message'] = "Rezerwacja została pomyślnie złożona na $reservation_date dla $guests gości!";
                    header('Location: /rezerwuj');
                } else {
                    echo "Błąd podczas tworzenia rezerwacji!";
                }
            }
        }
    }
    //API: /cancel-reservation
    //Odwołuje rezerwacje tj. zmienia w bazie kolumne cancelled na 1
    //Zwraca JSON ze statusem czy się udało
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

    //API: /restore-reservation
    //Przywraca rezerwacje tj. zmienia w bazie kolumne cancelled na 0
    //Zwraca JSON ze statusem czy się udało
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
