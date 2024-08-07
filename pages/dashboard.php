<?php
session_start();
require_once '../conection/db.php';

 
// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener información del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, role FROM users WHERE id = ?");
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $role);
$stmt->fetch();
$stmt->close();

// Variables para mensajes
$profile_update_message = '';
$password_change_message = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_email = $_POST['email'];
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }
        $stmt->bind_param("si", $new_email, $user_id);
        if ($stmt->execute()) {
            $profile_update_message = "Perfil actualizado";
            $email = $new_email; // Actualizar la variable para reflejar el nuevo email en el formulario
        } else {
            $profile_update_message = "Error al actualizar perfil";
        }
        $stmt->close();
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Verificar la contraseña actual
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $hashed_password)) {
            // Actualizar la contraseña
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt === false) {
                die('Error en la preparación de la consulta: ' . $conn->error);
            }
            $stmt->bind_param("si", $new_hashed_password, $user_id);
            if ($stmt->execute()) {
                $password_change_message = "Contraseña cambiada";
            } else {
                $password_change_message = "Error al cambiar la contraseña";
            }
            $stmt->close();
        } else {
            $password_change_message = "Contraseña actual incorrecta";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
   <?php include '../templates/dashContainer.php';?>
</body>
</html>
