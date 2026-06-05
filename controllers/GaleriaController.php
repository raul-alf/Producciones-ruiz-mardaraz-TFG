<?php

class GaleriaController extends Controller
{
    public function index()
    {
        $this->view('galeria/index');
    }

    public function verAlbum()
    {
        $this->view('galeria/ver-album');
    }
    
}
