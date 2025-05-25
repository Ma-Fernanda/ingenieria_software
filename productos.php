<?php
session_start();
include 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Consulta para obtener productos
$res = $conexion->query("SELECT * FROM productos");
$datos = [];

while ($row = $res->fetch_assoc()) {
    $datos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>