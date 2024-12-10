<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

include 'config.php'; // Asegúrate de que config.php tiene la conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['Email'];

    // Paso 1: Buscar el ID_usuario en Usuario
    $stmt = $conn->prepare("SELECT ID_usuario FROM Usuario WHERE Email = ?");
    if (!$stmt) {
        die("Error en la preparación de la consulta para obtener ID_usuario: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['ID_usuario'];

        // Paso 2: Generar un token único y la fecha de expiración
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Paso 3: Insertar o actualizar el token en PasswordReset
        $stmt = $conn->prepare("INSERT INTO PasswordReset (ID_usuario, Token, Expires) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE Token=?, Expires=?");
        if (!$stmt) {
            die("Error en la preparación de la consulta de inserción/actualización: " . $conn->error);
        }

        $stmt->bind_param("sssss", $userId, $token, $expires, $token, $expires);

        // Paso 4: Ejecutar la inserción y verificar si fue exitosa
        if ($stmt->execute()) {
            // Configurar PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'mail.nublerecicla.cl';
                $mail->SMTPAuth = true;
                $mail->Username = 'envio.mail@nublerecicla.cl';
                $mail->Password = '#GD_%Msf4Hu?';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->CharSet = 'UTF-8';

                // Remitente y destinatario
                $mail->setFrom('envio.mail@nublerecicla.cl', 'Ñuble Recicla');
                $mail->addAddress($email);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Restablecimiento de Contraseña';
                $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='https://nublerecicla.cl/api/reset_password.php?token=$token'>$token</a>";

                if ($mail->send()) {
                    echo json_encode(["success" => true, "message" => "Enlace de restablecimiento enviado."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error al enviar el correo de restablecimiento."]);
                }
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Error al enviar el correo: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error al insertar el token en la base de datos: " . $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Correo no registrado."]);
    }

    $stmt->close();
    $conn->close();
}
?>
