<?php
session_start();
require '../vendor/autoload.php'; // Incluye el autoload de Composer para PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$sweetalert_css = 'node_modules/sweetalert2/dist/sweetalert2.min.css';
$sweetalert_js = 'node_modules/sweetalert2/dist/sweetalert2.all.min.js';
require '../conection/db.php';

// Validación y manejo del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $created_at = date('Y-m-d H:i:s'); // Crear una marca de tiempo para created_at
    $is_confirmed = 0; // Inicializar is_confirmed a 0

    // Generar un token de confirmación
    $token = bin2hex(random_bytes(50)); // Token de 100 caracteres

    // Insertar usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at, role, token, is_confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }
    $stmt->bind_param("ssssssi", $username, $email, $password, $created_at, $role, $token, $is_confirmed);
    if ($stmt->execute()) {
        // Enviar correo de confirmación
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
            $mail->Subject = 'Confirma tu Cuenta';
            $mail->Body    = 'Por favor, confirma tu cuenta haciendo clic en el siguiente enlace: <a href="http://localhost/LoginPrueba/confirm.php?token=' . $token . '">Confirmar Cuenta</a>';
            $mail->AltBody = 'Por favor, confirma tu cuenta haciendo clic en el siguiente enlace: http://localhost/LoginPrueba/confirm.php?token=' . $token;

            $mail->send();

            // Mostrar SweetAlert de éxito
            echo '<link rel="stylesheet" href="' . $sweetalert_css . '">';
            echo '<script src="' . $sweetalert_js . '"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Usuario registrado correctamente",
                        text: "El mensaje de confirmación ha sido enviado, revise el correo con el que se registró.",
                        icon: "success",
                        confirmButtonText: "Ir a Iniciar Sesión"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "login.php";
                        }
                    });
                });
            </script>';
        } catch (Exception $e) {
            echo '<link rel="stylesheet" href="' . $sweetalert_css . '">';
            echo '<script src="' . $sweetalert_js . '"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Error",
                        text: "El mensaje no pudo ser enviado. Mailer Error: ' . $mail->ErrorInfo . '",
                        icon: "error",
                        confirmButtonText: "Intentar de nuevo"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "index.php";
                        }
                    });
                });
            </script>';
        }
    } else {
        echo '<link rel="stylesheet" href="' . $sweetalert_css . '">';
            echo '<script src="' . $sweetalert_js . '"></script>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Error",
                    text: "Error al registrar el usuario: ' . $stmt->error . '",
                    icon: "error",
                    confirmButtonText: "Intentar de nuevo"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index.php";
                    }
                });
            });
        </script>';
    }
    $stmt->close();
    $conn->close();
}
?>
