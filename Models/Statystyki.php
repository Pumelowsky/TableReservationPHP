<?php
class Statystyki
{
    public static function getReservationStatistics($pdo)
    {
        $query = "
            SELECT reservation_date, 
                   COUNT(*) AS total_reservations, 
                   SUM(guests) AS total_guests
            FROM reservations
            GROUP BY reservation_date
            ORDER BY reservation_date DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
