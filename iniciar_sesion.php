<?php
header('Content-Type: application/json');
session_start();

$conexion = new mysqli("localhost", "root", "", "tienda_de_ropa");

if ($conexion->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

// Obtener datos
$correo = $conexion->real_escape_string($_POST['correo'] ?? '');
$contrasenia = $_POST['contrasenia'] ?? '';

// Validar campos
if (empty($correo) || empty($contrasenia)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son requeridos"]);
    exit;
}

// Buscar usuario
$stmt = $conexion->prepare("SELECT id, contrasenia FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    exit;
}

$usuario = $result->fetch_assoc();

// Verificar contraseña
if (!password_verify($contrasenia, $usuario['contrasenia'])) {
    echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    exit;
}

// Iniciar sesión
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['correo'] = $correo;

echo json_encode([
    "success" => true, 
    "message" => "Inicio de sesión exitoso",
    "redirect" => "catalogo.html"
]);
?>
