<?php
require './conection/db.php';
// Define URLs para SweetAlert2
$sweetalert_css = '/node_modules/sweetalert2/dist/sweetalert2.min.css';
$sweetalert_js = '/node_modules/sweetalert2/dist/sweetalert2.all.min.js';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Buscar el usuario con el token proporcionado
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ? AND is_confirmed = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Actualizar el usuario como confirmado
        $stmt->prepare("UPDATE users SET is_confirmed = 1, token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            echo '<link rel="stylesheet" href="' . $sweetalert_css . '">';
            echo '<script src="' . $sweetalert_js . '"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "¡Cuenta confirmada!",
                        text: "¡Cuenta confirmada con éxito!",
                        icon: "success",
                        confirmButtonText: "Iniciar sesión"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "login.php";
                        }
                    });
                });
            </script>';
        } else {
            echo '<link rel="stylesheet" href="' . $sweetalert_css . '">';
            echo '<script src="' . $sweetalert_js . '"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Error",
                        text: "Error al confirmar la cuenta. Intenta nuevamente.",
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
                    text: "Token de confirmación inválido o ya utilizado.",
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
} else {
    echo '<link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">';
    echo '<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "Error",
                text: "No se proporcionó un token de confirmación.",
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
