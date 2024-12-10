<?php
header('Content-Type: application/json');
include 'config.php'; // Conexión a la base de datos

// Comprobar conexión
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos: " . $conn->connect_error
    ]);
    exit();
}

// Verificar que se recibieron email y password
if (isset($_POST['Email']) && isset($_POST['Password'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // Preparar y ejecutar la consulta para encontrar al usuario
    $sql = "SELECT ID_usuario, Password, Verificacion FROM Usuario WHERE Email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode([
            "success" => false,
            "message" => "Error en la preparación de la consulta: " . $conn->error
        ]);
        exit();
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Error al ejecutar la consulta: " . $stmt->error
        ]);
        exit();
    }

    $result = $stmt->get_result();

    // Verificar si el usuario fue encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['Password'])) {
            // Verificar si el usuario está verificado
            if ($user['Verificacion'] == 1) {
                echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso."]);
            } else {
                echo json_encode(["success" => false, "message" => "Por favor, verifique su correo electrónico."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
    }

    $stmt->close();
} else {
    // Respuesta en caso de que no se envíen email o password
    echo json_encode(["success" => false, "message" => "Email y contraseña son requeridos."]);
}

$conn->close();
