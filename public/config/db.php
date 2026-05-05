<?php
/**
 * Configuración de conexión a la Base de Datos IONOS
 */

// Datos extraídos de tu volcado SQL
$host = 'db5019517939.hosting-data.io'; // Servidor IONOS
$db   = 'dbs15258558';                  // Nombre de la base de datos
$user = 'dbu2058633';                   // DEBES BUSCAR TU USUARIO EN EL PANEL DE IONOS
$pass = 'LaLuna2026.';           // LA CONTRASEÑA QUE CONFIGURASTE EN IONOS
$charset = 'utf8mb4';                   // Basado en NAMES utf8mb4 del SQL

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // En producción, es mejor no mostrar el mensaje de error directamente
     die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>