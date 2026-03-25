<?php
/**
 * ContactModel — obsługa kontaktów w systemie
 */
class ContactModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM contacts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO contacts (first_name, last_name, email, status, created_at)
             VALUES (?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['status'] ?? 'Active',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Zwraca aktywne kontakty przypisane do konta.
     * Używana w widoku Accounts Detail do wyświetlania powiązanych kontaktów.
     */
    public function getActiveByAccount(int $accountId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, first_name, last_name, email
             FROM contacts
             WHERE account_id = ? AND status = \'Active\'
             ORDER BY last_name, first_name'
        );
        $stmt->execute([$accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
