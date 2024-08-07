<div class="logo-container">
  <img src="./img/eAmax.png" alt="Tu Logo" class="logo" />
</div>
<div class="form-container">
    <!-- Registration Form -->
    <form id="register-form" class="form" action="pages/register.php" method="POST" style="display: block;">
        <h2>Registro</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="role">Rol:</label>
        <select id="role" name="role">
            <option value="2">Repartidor</option>
            <option value="3">Usuario</option>
        </select>
        <input type="submit" value="Registrarse">
        <a href="#" id="show-login-form">¿Ya tienes cuenta? Inicia sesión</a>
    </form>

    <!-- Login Form -->
    <form id="login-form" class="form" action="pages/login.php" method="POST" style="display: none;">
        <h2>Iniciar Sesión</h2>
        <label for="login-username">Username:</label>
        <input type="text" id="login-username" name="login-username" required><br><br>

        <label for="login-password">Password:</label>
        <input type="password" id="login-password" name="login-password" required><br><br>

        <input type="submit" value="Ingresar">
        <a href="#" id="show-register-form">¿No tienes cuenta? Regístrate</a>
        <a href="./pages/forgot_password.php">¿Olvidaste tu contraseña?</a>
    </form>
</div>
