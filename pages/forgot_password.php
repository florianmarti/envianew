<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Incluye el autoload de Composer para PHPMailer

 

require '../conection/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo electrónico está registrado
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username);
        $stmt->fetch();

        // Generar un token de recuperación
        $token = bin2hex(random_bytes(32));

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = NOW() + INTERVAL 1 HOUR WHERE email = ?");
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Enviar el correo electrónico
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; // Servidor SMTP de Mailtrap
            $mail->SMTPAuth   = true;
            $mail->Username   = '724acf8e24d3e4'; // Tu nombre de usuario Mailtrap
            $mail->Password   = '1e0996d8d4b291'; // Tu contraseña Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('no-reply@example.com', 'loginApp');
            $mail->addAddress($email); // El correo del usuario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body    = 'Hola ' . htmlspecialchars($username) . ',<br><br>Haz clic en el siguiente enlace para recuperar tu contraseña:<br><br>';
            $mail->Body   .= '<a href="http://localhost/LoginPrueba/reset_password.php?token=' . $token . '">Recuperar Contraseña</a><br><br>';
            $mail->Body   .= 'Este enlace caducará en 1 hora.<br><br>Saludos,<br>loginApp';
            $mail->AltBody = 'Hola ' . htmlspecialchars($username) . ',\n\nHaz clic en el siguiente enlace para recuperar tu contraseña:\n\n';
            $mail->AltBody .= 'http://localhost/LoginPrueba/reset_password.php?token=' . $token . '\n\n';
            $mail->AltBody .= 'Este enlace caducará en 1 hora.\n\nSaludos,\nloginApp';

            $mail->send();
            $success = "Se ha enviado un enlace de recuperación a tu correo electrónico.";
        } catch (Exception $e) {
            $error = "Hubo un problema al enviar el correo electrónico: {$mail->ErrorInfo}";
        }
    } else {
        $error = "El correo electrónico no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Recuperación de Contraseña</h2>
        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success-message'>$success</p>";
        }
        ?>
        <form method="POST" action="forgot_password.php" class="form">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <input type="submit" value="Enviar enlace de recuperación">
        </form>
        <p><a href="login.php">Volver al inicio de sesión</a></p>
    </div>
</body>
</html>
