<?php

class HomeController extends Controller
{
    public function index()
    {
       $djs = [
    ['name' => 'DJ ADONYS', 'role' => 'URBAN DJ', 'img' => BASE_URL . 'img/DSC00354.jpg'],
    ['name' => 'DJ RAUL MALO', 'role' => 'URBAN DJ', 'img' => BASE_URL . 'img/IMG_2071.jpg'],
    ['name' => 'DJ ALBERTO AGUIRRE', 'role' => 'TECHNO / GROOVE', 'img' => BASE_URL . 'img/DSC02281.jpg'],
    ['name' => 'DJ NCNH', 'role' => 'TECHNO / GROOVE', 'img' => BASE_URL . 'img/DSC02243.jpg']
];

        $this->view('home', [
            'djs' => $djs
        ]);
    }
}