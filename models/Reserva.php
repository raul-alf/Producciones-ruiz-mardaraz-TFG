<?php

class Reserva
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
       public function getAll()
    {
        $sql = "SELECT * FROM reservas ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $fecha, $personas, $telefono, $evento)
    {
        $sql = "INSERT INTO reservas
                (nombre, fecha, personas, telefono, evento, estado)
                VALUES
                (:nombre, :fecha, :personas, :telefono, :evento, 'Pendiente')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':fecha' => $fecha,
            ':personas' => $personas,
            ':telefono' => $telefono,
            ':evento' => $evento
        ]);
    }

 

    public function getById($id)
    {
        $sql = "SELECT * FROM reservas WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM reservas WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE reservas
                SET estado = :estado
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }

    public function totalReservas()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM reservas");

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function reservasPendientes()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total
            FROM reservas
            WHERE estado='Pendiente'
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function reservasConfirmadas()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total
            FROM reservas
            WHERE estado='Confirmada'
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
}