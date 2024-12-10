<?php
include 'config.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Verificar que el usuario con el email y token existe en la base de datos
    $checkUserQuery = "SELECT * FROM Usuario WHERE Email = ? AND ID_usuario = ? AND Verificacion = 0";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Actualizar el estado de verificación a 1
        $updateQuery = "UPDATE Usuario SET Verificacion = 1 WHERE Email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            echo "Su cuenta ha sido verificada exitosamente. Ahora puede iniciar sesión.";
        } else {
            echo "Hubo un error al actualizar el estado de verificación. Intente de nuevo más tarde.";
        }
    } else {
        echo "Este enlace de verificación no es válido o la cuenta ya está verificada.";
    }

    $stmt->close();
} else {
    echo "Datos de verificación no válidos.";
}

$conn->close();
?>
