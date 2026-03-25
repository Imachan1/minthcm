<?php
/**
 * ReportController — generowanie raportów sprzedażowych
 */
class ReportController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Generuje raport sprzedaży za podany zakres dat.
     * Akceptuje daty w formacie d.m.Y (format używany w UI).
     */
    public function generateSalesReport(string $dateFrom, string $dateTo): array
    {
        // FIX: zmieniono format z Y-m-d na d.m.Y zgodnie z formatem UI
        $from = DateTime::createFromFormat('d.m.Y', $dateFrom);
        $to   = DateTime::createFromFormat('d.m.Y', $dateTo);

        if (!$from || !$to) {
            return ['error' => 'Nieprawidłowy format daty. Użyj formatu DD.MM.RRRR'];
        }

        if ($from > $to) {
            return ['error' => 'Data od nie może być późniejsza niż data do'];
        }

        $stmt = $this->db->prepare(
            'SELECT SUM(amount) as total, COUNT(*) as count
             FROM opportunities
             WHERE close_date BETWEEN ? AND ?
               AND sales_stage = \'Closed Won\''
        );
        $stmt->execute([$from->format('Y-m-d'), $to->format('Y-m-d')]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}
