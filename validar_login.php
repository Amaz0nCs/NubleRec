<?php
session_start(); // Inicia la sesión

// Incluir el archivo de configuración con la conexión a la base de datos
require 'api/config.php';

header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Asegura que la respuesta sea en formato JSON

// Obtener los datos enviados como JSON
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $input['email'];
    $password = $input['password'];

    // Consulta para verificar el usuario
    $sql = "SELECT ID_Usuario, Nombre FROM Usuario WHERE Email = ? AND Password = ? AND Tipo_Usuario = ?";
    $stmt = $conn->prepare($sql);

    $tipo_usuario = 2; // Tipo de usuario válido
    $stmt->bind_param("ssi", $email, $password, $tipo_usuario);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario autenticado correctamente
        $user = $result->fetch_assoc();

        // Guardar información del usuario en la sesión
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['ID_Usuario'];
        $_SESSION['user_name'] = $user['Nombre'];

        // Respuesta de éxito
        echo json_encode([
            "success" => true,
            "message" => "¡Login exitoso!",
            "user_name" => $user['Nombre']
        ]);
    } else {
        // Respuesta de error si las credenciales no son válidas
        echo json_encode([
            "success" => false,
            "message" => "Correo o contraseña incorrectos."
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
