<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido");
    }

    $conexion = new mysqli("localhost", "root", "", "tienda_de_ropa");
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener y sanitizar datos
    $nombre = $conexion->real_escape_string($_POST['nombre'] ?? '');
    $correo = $conexion->real_escape_string($_POST['correo'] ?? '');
    $celular = $conexion->real_escape_string($_POST['celular'] ?? '');
    $contrasenia = $_POST['contrasenia'] ?? '';

    error_log("Datos recibidos - Nombre: $nombre, Correo: $correo, Celular: $celular");

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasenia)) {
        throw new Exception("Todos los campos obligatorios deben estar completos");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo electrónico no es válido");
    }

    if (strlen($contrasenia) < 6) {
        throw new Exception("La contraseña debe tener al menos 6 caracteres");
    }

    // Verificar si el email ya existe
    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    if ($stmt_check === false) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt_check->bind_param("s", $correo);
    if (!$stmt_check->execute()) {
        throw new Exception("Error al verificar correo: " . $stmt_check->error);
    }

    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        throw new Exception("El correo electrónico ya está registrado");
    }
    $stmt_check->close();

    // Hash de la contraseña
    $passwordHash = password_hash($contrasenia, PASSWORD_BCRYPT);
    if ($passwordHash === false) {
        throw new Exception("Error al generar el hash de la contraseña");
    }

    // Insertar nuevo usuario
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, celular, contrasenia) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("Error al preparar inserción: " . $conexion->error);
    }

    $stmt->bind_param("ssss", $nombre, $correo, $celular, $passwordHash);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al registrar usuario: " . $stmt->error);
    }

    // Éxito
    echo json_encode([
        "success" => true,
        "message" => "Registro exitoso.",
        "redirect" => "iniciar_sesion.html"
    ]);

} catch (Exception $e) {
    // Error capturado
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
} finally {
    // Cerrar conexiones si existen
    if (isset($stmt_check) && $stmt_check) $stmt_check->close();
    if (isset($stmt) && $stmt) $stmt->close();
    if (isset($conexion) && $conexion) $conexion->close();
}
?>