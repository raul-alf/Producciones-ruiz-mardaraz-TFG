<?php

class Evento
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query(
            "SELECT * FROM events ORDER BY date ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVisible()
    {
        $stmt = $this->db->query(
            "SELECT * FROM events WHERE oculto = 0 ORDER BY date ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarVisibilidad($id, $oculto)
    {
        $sql = "UPDATE events 
            SET oculto = :oculto 
            WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':oculto' => $oculto,
        ':id' => $id
    ]);
}
public function crear($title, $date, $image)
{
    $sql = "INSERT INTO events (title, date, image)
            VALUES (:title, :date, :image)";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':title' => $title,
        ':date' => $date,
        ':image' => $image
    ]);
}
public function eliminar($id)
{
    $sql = "DELETE FROM events WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':id' => $id
    ]);
}
public function getById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM events WHERE id = :id");

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}