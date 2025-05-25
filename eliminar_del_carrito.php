<?php
session_start();

$id = $_POST['id'];
$usuario_id = $_SESSION['usuario_id'] ?? 0;

if (isset($_SESSION['carrito'][$usuario_id][$id])) {
    unset($_SESSION['carrito'][$usuario_id][$id]);
}

echo json_encode(["status" => "ok", "mensaje" => "Producto eliminado"]);
?>
