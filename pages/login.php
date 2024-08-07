<?php
session_start();
require '../conection/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    // Consulta para obtener el usuario con el nombre de usuario proporcionado
    $stmt = $conn->prepare("SELECT id, username, password, is_confirmed FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $is_confirmed);
        $stmt->fetch();

        // Verificar si la contraseña es correcta
        if (password_verify($password, $hashed_password)) {
            // Verificar si la cuenta está confirmada
            if ($is_confirmed == 1) {
                // Iniciar sesión y redirigir al dashboard
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Debes verificar tu correo electrónico para activar tu cuenta.";
            }
        } else {
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    } else {
        $error = "Correo electrónico o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <div class="form-container">
        <h2>Inicio de sesión</h2>
        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
        <form method="POST" action="login.php" class="form">
            <label for="login-username">Username:</label>
            <input type="text" id="login-username" name="login-username" required><br><br>
            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="login-password" required><br><br>
            <input type="submit" value="Iniciar sesión">
        </form>
        <p><a href="forgot_password.php">¿Olvidaste tu contraseña?</a></p>
    </div>
</body>
</html>
