<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

include 'config.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

$email = $_POST['Email'];
$nombre = $_POST['Nombre'];
$apellidoP = $_POST['ApellidoP'];
$apellidoM = $_POST['ApellidoM'];
$password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
$tipoUsuario = 0;
$idUsuario = $_POST['ID_usuario'];

// Verificar si el correo ya está registrado
$checkEmailQuery = "SELECT * FROM Usuario WHERE Email = ?";
$stmt = $conn->prepare($checkEmailQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response['message'] = "El correo ya está registrado.";
} else {
    // Insertar nuevo usuario con verificación pendiente
    $query = "INSERT INTO Usuario (ID_usuario, Nombre, ApellidoP, ApellidoM, Email, Password, Tipo_Usuario, Verificacion) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $idUsuario, $nombre, $apellidoP, $apellidoM, $email, $password, $tipoUsuario);
    
    if ($stmt->execute()) {
        // Configurar el enlace de verificación
        $verificationLink = "https://nublerecicla.cl/api/verify.php?email=$email&token=$idUsuario";

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

            // Remitente y destinatario
            $mail->setFrom('envio.mail@nublerecicla.cl', 'Ñuble Recicla');
            $mail->addAddress($email, $nombre);

            // Codificación UTF-8 para el mensaje
            $mail->CharSet = 'UTF-8';

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Verificación de cuenta - Ñuble Recicla';
            $mail->Body = "Gracias por registrarte en Ñuble Recicla. Por favor, haz clic en el siguiente enlace para verificar tu cuenta: <a href='$verificationLink'>$verificationLink</a>";

            if ($mail->send()) {
                $response['success'] = true;
                $response['message'] = "Usuario registrado. Verifique su correo.";
            } else {
                $response['message'] = "No se pudo enviar el correo de verificación.";
            }
        } catch (Exception $e) {
            $response['message'] = "No se pudo enviar el correo de verificación. Error: {$mail->ErrorInfo}";
        }
    } else {
        $response['message'] = "Error en el registro.";
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
