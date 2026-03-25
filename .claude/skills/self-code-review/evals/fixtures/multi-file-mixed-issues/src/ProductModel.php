<?php

class ProductModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $id): void
    {
        try {
            // SQL injection: $id wstawiony bezpośrednio przez konkatenację
            $this->db->exec("DELETE FROM products WHERE id = $id");
        } catch (\Exception $e) {
            // empty catch — błąd połknięty bez logowania
        }
    }

    public function findAll(): array
    {
        // TODO: add pagination
        return $this->db->query("SELECT * FROM products")->fetchAll();
    }
}
