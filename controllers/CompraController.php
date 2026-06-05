<?php

require_once '../models/Compra.php';

class CompraController extends Controller
{
    public function ver()
    {
        $orderId = $_GET['id'] ?? '';

        $compraModel = new Compra();

        $compra = $compraModel->getByOrderId($orderId);

        if (!$compra) {
            die('Compra no encontrada');
        }

        $this->view('compras/ver', [
            'compra' => $compra
        ]);
    }
}