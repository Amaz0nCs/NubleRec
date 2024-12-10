<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT ID_usuario FROM PasswordReset WHERE Token = ? AND Expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resetData = $result->fetch_assoc();
        $userId = $resetData['ID_usuario'];
    } else {
        $message = "Enlace de restablecimiento no válido o expirado.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $message = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $newPassword)) {
        $message = "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.";
    } else {
        $stmt = $conn->prepare("SELECT ID_usuario FROM PasswordReset WHERE Token = ? AND Expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resetData = $result->fetch_assoc();
            $userId = $resetData['ID_usuario'];

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE Usuario SET Password = ? WHERE ID_usuario = ?");
            $stmt->bind_param("ss", $hashedPassword, $userId);

            if ($stmt->execute()) {
                // Eliminar el token de restablecimiento después de cambiar la contraseña
                $stmt = $conn->prepare("DELETE FROM PasswordReset WHERE ID_usuario = ?");
                $stmt->bind_param("s", $userId);
                $stmt->execute();
                
                // Mensaje de éxito
                $message = "Tu contraseña se restableció correctamente. Ahora puedes volver a la aplicación e iniciar sesión.";
            } else {
                $message = "Error al restablecer la contraseña. Por favor, intenta nuevamente.";
            }
        } else {
            $message = "Enlace de restablecimiento no válido o expirado.";
        }
    }
    $conn->close();
} else {
    $message = "Solicitud inválida.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f9f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            color: #388e3c;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #333;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            background-color: #388e3c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .password-checklist {
            margin-top: 15px;
            font-size: 0.9em;
            color: #555;
            text-align: left;
        }
        .password-checklist p {
            margin: 4px 0;
            display: flex;
            align-items: center;
        }
        .valid {
            color: green;
        }
        .invalid {
            color: red;
        }
        .icon {
            margin-right: 8px;
        }
        .message {
            font-size: 1.1em;
            margin-top: 20px;
            color: #388e3c;
        }
    </style>
    <script>
        function validatePassword(password) {
            document.getElementById('length').className = password.length >= 8 ? 'valid' : 'invalid';
            document.getElementById('uppercase').className = /[A-Z]/.test(password) ? 'valid' : 'invalid';
            document.getElementById('lowercase').className = /[a-z]/.test(password) ? 'valid' : 'invalid';
            document.getElementById('number').className = /\d/.test(password) ? 'valid' : 'invalid';
            document.getElementById('special').className = /[\W_]/.test(password) ? 'valid' : 'invalid';
            
            updateIcons();
        }

        function updateIcons() {
            const criteria = ['length', 'uppercase', 'lowercase', 'number', 'special'];
            criteria.forEach(id => {
                const element = document.getElementById(id);
                const icon = element.querySelector('.icon');
                if (element.className === 'valid') {
                    icon.innerHTML = '✓';
                } else {
                    icon.innerHTML = '✗';
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php else: ?>
            <form method="POST" action="reset_password.php">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <label>Nueva Contraseña</label>
                <input type="password" name="new_password" required onkeyup="validatePassword(this.value)">
                
                <label>Confirmar Contraseña</label>
                <input type="password" name="confirm_password" required>

                <div class="password-checklist">
                    <p id="length" class="invalid"><span class="icon">✗</span>Mínimo 8 caracteres</p>
                    <p id="uppercase" class="invalid"><span class="icon">✗</span>Al menos una letra mayúscula</p>
                    <p id="lowercase" class="invalid"><span class="icon">✗</span>Al menos una letra minúscula</p>
                    <p id="number" class="invalid"><span class="icon">✗</span>Al menos un número</p>
                    <p id="special" class="invalid"><span class="icon">✗</span>Al menos un carácter especial</p>
                </div>

                <button type="submit">Restablecer Contraseña</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
