<?php
session_start();

// Verificamos si se recibió un ID de producto
if (!isset($_POST['producto_id'])) {
    http_response_code(400);
    echo "ID de producto no recibido.";
    exit;
}

$producto_id = intval($_POST['producto_id']);

// Aquí iría la conexión a la base de datos para obtener los detalles del producto
$conn = new mysqli("localhost", "root", "", "tienda_de_ropa");

if ($conn->connect_error) {
    http_response_code(500);
    echo "Error al conectar con la base de datos.";
    exit;
}

$sql = "SELECT id, nombre, precio FROM productos WHERE id = $producto_id";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $producto = $resultado->fetch_assoc();

    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Si el producto ya está en el carrito, solo aumentamos la cantidad
    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad'] += 1;
    } else {
        // Agregar nuevo producto al carrito
        $_SESSION['carrito'][$producto_id] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => 1
        ];
    }

    echo "Producto agregado correctamente.";
} else {
    http_response_code(404);
    echo "Producto no encontrado.";
}

$conn->close();
?>
