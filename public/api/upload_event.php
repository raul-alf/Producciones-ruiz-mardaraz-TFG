<?php
header("Content-Type: application/json");
require_once '../config/db.php';

try {
    if (isset($_FILES['flyer'])) {
        $name = time() . "_" . basename($_FILES['flyer']['name']);
        
        // IMPORTANTE: La ruta debe subir un nivel para salir de 'api/' y entrar en 'img/'
        $folder = "../img/eventos/";
        
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true); // Crea la carpeta si no existe
        }
        
        $path_fisico = $folder . $name;

        if (move_uploaded_file($_FILES['flyer']['tmp_name'], $path_fisico)) {
            $path_db = "img/eventos/" . $name;
            
            // Usamos 'title' porque tu base de datos dio error con ese campo antes
            $stmt = $pdo->prepare("INSERT INTO events (title, date, image) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['status'], $_POST['date'], $path_db]);
            
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo mover el archivo. Revisa permisos de 'img/eventos/'"]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}