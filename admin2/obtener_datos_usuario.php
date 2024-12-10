<?php
include 'config.php';

if (!isset($_GET['Email'])) {
    echo json_encode(["success" => false, "message" => "Correo no proporcionado."]);
    exit();
}

$email = $_GET['Email'];

// Consulta para obtener los datos del usuario
$query = "SELECT Nombre, ApellidoP, ApellidoM, Email FROM Usuario WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $user]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
}

$stmt->close();
$conn->close();
?>
