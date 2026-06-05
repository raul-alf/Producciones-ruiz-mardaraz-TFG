<?php

require_once '../models/Promo.php';

class PromoController extends Controller
{
    public function index()
    {
        $promoModel = new Promo();
        $promos = $promoModel->getAll();

        $this->view('promos/index', [
            'promos' => $promos
        ]);
    }
}