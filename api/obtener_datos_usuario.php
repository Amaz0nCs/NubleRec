<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir el archivo de configuraci車n para conectar a la base de datos
include 'config.php';

// Obtener el correo desde POST o GET
$email = $_POST['Email'] ?? $_GET['Email'] ?? null;

// Validar que se recibi車 el correo
if (empty($email)) {
    echo json_encode(["success" => false, "message" => "Correo no proporcionado"]);
    exit();
}

// Preparar y ejecutar la consulta SQL
$sql = "SELECT Nombre, ApellidoP, ApellidoM, Email FROM Usuario WHERE Email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparaci車n de la consulta.",
        "error" => $conn->error
    ]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $userData]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
}

// Cerrar la conexi車n
$stmt->close();
$conn->close();
?>
