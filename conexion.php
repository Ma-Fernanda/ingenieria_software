<?php
$conexion = new mysqli("localhost", "root", "", "tienda_de_ropa");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
