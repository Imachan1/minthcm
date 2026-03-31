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
     * BUG: parsuje datę w formacie Y-m-d zamiast d.m.Y (format używany w UI)
     */
    public function generateSalesReport(string $dateFrom, string $dateTo): array
    {
        // BUG: format Y-m-d nie pasuje do dat wpisywanych przez użytkownika (d.m.Y)
        $from = DateTime::createFromFormat('Y-m-d', $dateFrom);
        $to   = DateTime::createFromFormat('Y-m-d', $dateTo);

        if (!$from || !$to) {
            return ['error' => 'Nieprawidłowy format daty'];
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
