<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

   public function buscarPorUsuario($usuario)
{
    $sql = "SELECT * FROM usuarios_admin WHERE username = :usuario LIMIT 1";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':usuario' => $usuario
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}