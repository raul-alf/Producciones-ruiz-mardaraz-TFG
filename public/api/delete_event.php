<?php
require_once '../config/db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
echo json_encode(["success" => true]);