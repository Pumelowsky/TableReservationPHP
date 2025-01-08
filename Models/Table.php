<?php
class Table
{
    public static function getAvailableTables($pdo, $reservation_date)
    {
        $query = "
            SELECT t.id, t.table_number, t.seats
            FROM tables t
            WHERE t.id NOT IN (
                SELECT r.table_id
                FROM reservations r
                WHERE r.reservation_date = :reservation_date
                AND (r.cancelled = 0)
            )
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
