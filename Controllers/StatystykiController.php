<?php
class StatystykiController
{
    //Funkcja do pobrania statystyk i ustawienia ich w formacie dla Chart.js
    public function getStatistics()
    {
        global $pdo;
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo = $_GET['date_to'] ?? null;

        $reservationsQuery = "SELECT DATE(reservation_date) as date, COUNT(*) as count FROM reservations WHERE cancelled = 0";
        if ($dateFrom) {
            $reservationsQuery .= " AND reservation_date >= :date_from";
        }
        if ($dateTo) {
            $reservationsQuery .= " AND reservation_date <= :date_to";
        }
        $reservationsQuery .= " GROUP BY DATE(reservation_date) ORDER BY DATE(reservation_date)";

        $stmt = $pdo->prepare($reservationsQuery);
        if ($dateFrom) $stmt->bindParam(':date_from', $dateFrom);
        if ($dateTo) $stmt->bindParam(':date_to', $dateTo);
        $stmt->execute();
        $reservationsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cancelledQuery = "SELECT DATE(reservation_date) as date, COUNT(*) as count FROM reservations WHERE cancelled = 1";
        if ($dateFrom) {
            $cancelledQuery .= " AND reservation_date >= :date_from";
        }
        if ($dateTo) {
            $cancelledQuery .= " AND reservation_date <= :date_to";
        }
        $cancelledQuery .= " GROUP BY DATE(reservation_date) ORDER BY DATE(reservation_date)";

        $stmt = $pdo->prepare($cancelledQuery);
        if ($dateFrom) $stmt->bindParam(':date_from', $dateFrom);
        if ($dateTo) $stmt->bindParam(':date_to', $dateTo);
        $stmt->execute();
        $cancelledData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reservationsLabels = array_column($reservationsData, 'date');
        $reservationsValues = array_column($reservationsData, 'count');

        $cancelledLabels = array_column($cancelledData, 'date');
        $cancelledValues = array_column($cancelledData, 'count');

        return [
            'reservationsData' => [
                'labels' => $reservationsLabels,
                'values' => $reservationsValues
            ],
            'cancelledData' => [
                'labels' => $cancelledLabels,
                'values' => $cancelledValues
            ]
        ];
    }
}
