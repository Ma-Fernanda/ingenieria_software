<?php
session_start();
include('conexion.php'); // asegúrate de tener esto para conectarte a la base de datos

// Simulación de ID de usuario (puedes cambiar esto cuando implementes login)
$usuario_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["producto_id"])) {
    $producto_id = $_POST["producto_id"];

    // Verifica si el producto ya está en el carrito
    $check = "SELECT * FROM carrito WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        // Ya existe, actualiza cantidad
        $update = "UPDATE carrito SET cantidad = cantidad + 1 WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
        mysqli_query($conn, $update);
    } else {
        // No existe, lo inserta
        $insert = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, 1)";
        mysqli_query($conn, $insert);
    }

    // Redirecciona al carrito
    header("Location: carrito.php");
    exit();
} else {
    echo "Error: No se recibió el ID del producto.";
}
?>
