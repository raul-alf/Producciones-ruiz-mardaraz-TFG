<?php

class Compra
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getByOrderId($orderId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM compras WHERE order_id = ?"
        );

        $stmt->execute([$orderId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}