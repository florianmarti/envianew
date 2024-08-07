<?php
session_start();
require '../conection/db.php';

$error = '';
$success = '';

// Verificar si el token está en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $error = 'El enlace de recuperación es inválido.';
}

// Procesar el formulario de restablecimiento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($token)) {
        $new_password = $_POST['password'];

        // Verificar el token
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Actualizar la contraseña
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $hashed_password, $token);
            $stmt->execute();

            // Redirigir al usuario a la página de inicio de sesión
            header("Location: index.php?message=password_updated");
            exit();
        } else {
            $error = "El enlace de recuperación ha caducado o es inválido.";
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/normalize.css">
</head>
<body>
    <div class="form-container">
        <h2>Restablecer Contraseña</h2>
        <?php
        if ($error) {
            echo "<p class='error-message'>$error</p>";
        }
        if ($success) {
            echo "<p class='success-message'>$success</p>";
        }
        ?>
        <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" class="form">
            <label for="password">Nueva contraseña:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Actualizar contraseña">
        </form>
    </div>
</body>
</html>
