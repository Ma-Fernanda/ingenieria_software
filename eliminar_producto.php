<?php
session_start();
$id  = intval($_POST['id']);
$uid = $_SESSION['usuario_id'] ?? 0;

if (isset($_SESSION['carrito'][$uid][$id])) {
    unset($_SESSION['carrito'][$uid][$id]);
}
echo json_encode(array_values($_SESSION['carrito'][$uid] ?? []));
?>

