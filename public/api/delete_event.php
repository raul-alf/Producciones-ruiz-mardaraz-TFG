<?php
require_once __DIR__ . '/../../core/Database.php';
$pdo = Database::connect();
$id = $_GET['id'];
$pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
echo json_encode(["success" => true]);