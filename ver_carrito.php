<?php
session_start();
$uid    = $_SESSION['usuario_id'] ?? 0;
$carrito = array_values($_SESSION['carrito'][$uid] ?? []);
header('Content-Type: application/json');
echo json_encode($carrito);
?>
