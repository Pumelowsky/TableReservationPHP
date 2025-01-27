<?php

class Reservation
{
    private $table_id;
    private $user_id;
    private $guests;
    private $reservation_date;
    private $is_cancelled;

    //Konstruktor
    public function __construct($table_id, $user_id, $guests, $reservation_date, $is_cancelled)
    {
        $this->table_id = $table_id;
        $this->user_id = $user_id;
        $this->guests = $guests;
        $this->reservation_date = $reservation_date;
        $this->is_cancelled = $is_cancelled;
    }

    //Obsługa do bazy tworzenia rezerwacji
    public static function createReservation($pdo, $table_id, $user_id, $guests, $reservation_date)
    {
        $query = "INSERT INTO reservations (table_id, user_id, guests, reservation_date) 
                  VALUES (:table_id, :user_id, :guests, :reservation_date)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':table_id', $table_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':guests', $guests);
        $stmt->bindParam(':reservation_date', $reservation_date);
        return $stmt->execute();
    }
    //Obsługa do bazy odwoływania rezerwacji
    public static function cancelReservation($pdo, $reservation_id)
    {
        $query = "UPDATE reservations SET cancelled = TRUE WHERE id = :reservation_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':reservation_id', $reservation_id);
        return $stmt->execute();
    }
    //Pobieranie wszystkich rezerwacji z bazy z filtrami
    public static function getReservationsWithFilters($pdo, $filters)
    {
        $query = "SELECT r.id, r.table_id, r.user_id, r.guests, r.reservation_date, r.cancelled, 
                         t.table_number, u.username
                  FROM reservations r
                  JOIN tables t ON r.table_id = t.id
                  JOIN users u ON r.user_id = u.id";

        if (!empty($filters['status'])) {
            if ($filters['status'] == 'active') {
                $query .= " AND r.cancelled = 0";
            } elseif ($filters['status'] == 'cancelled') {
                $query .= " AND r.cancelled = 1";
            }
        }

        if (!empty($filters['date_from'])) {
            $query .= " AND r.reservation_date >= :date_from";
        }

        if (!empty($filters['date_to'])) {
            $query .= " AND r.reservation_date <= :date_to";
        }

        $stmt = $pdo->prepare($query);

        if (!empty($filters['date_from'])) {
            $stmt->bindParam(':date_from', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $stmt->bindParam(':date_to', $filters['date_to']);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Pobieranie rezerwacji z bazy dla danego użytkownika (po user_id)
    public static function getUserReservations($pdo, $user_id)
    {
        $query = "SELECT r.id, r.table_id, r.reservation_date, r.guests, t.table_number, r.cancelled
                  FROM reservations r
                  JOIN tables t ON r.table_id = t.id
                  WHERE r.user_id = :user_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
