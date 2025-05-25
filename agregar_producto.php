<?php
session_start();

$id     = intval($_POST['id']);
$nombre = $_POST['nombre'];
$precio = floatval($_POST['precio']);

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = rand(1000, 9999);  // simulamos login
}
$uid = $_SESSION['usuario_id'];

if (!isset($_SESSION['carrito'][$uid])) {
    $_SESSION['carrito'][$uid] = [];
}

$_SESSION['carrito'][$uid][$id] = [
    "id"     => $id,
    "nombre" => $nombre,
    "precio" => $precio
];

echo json_encode(array_values($_SESSION['carrito'][$uid]));
?>
