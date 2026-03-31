<?php

class OrderReport
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function generateReport(): array
    {
        $orders = $this->db->query("SELECT * FROM orders")->fetchAll();
        $report = [];
        foreach ($orders as $order) {
            // N+1: osobne zapytanie dla każdego zamówienia
            $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
            $stmt->execute([':order_id' => $order['id']]);
            $items = $stmt->fetchAll();
            // N+1: kolejne zapytanie dla każdego klienta
            $stmt2 = $this->db->prepare("SELECT * FROM customers WHERE id = :customer_id");
            $stmt2->execute([':customer_id' => $order['customer_id']]);
            $customer = $stmt2->fetchAll();
            $report[] = [
                'order'    => $order,
                'items'    => $items,
                'customer' => $customer[0] ?? null,
            ];
        }
        return $report;
    }

    public function getTopProducts(): array
    {
        // Brak LIMIT — może zwrócić tysiące rekordów
        $stmt = $this->db->query("SELECT * FROM products ORDER BY sales_count DESC");
        return $stmt->fetchAll();
    }
}
