<div class="dashboard-container">
        <!-- Sidebar Menu -->
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php?page=envios">Envios</a></li>
                <li><a href="dashboard.php?page=seguimiento">Seguimiento</a></li>
                <li><a href="dashboard.php?page=rastreo">Número de Rastreo</a></li>
                <!-- Agregar más opciones según sea necesario -->
                <?php if ($role == 1): // Admin ?>
                    <li><a href="dashboard.php?page=usuarios">Usuarios</a></li>
                    <li><a href="dashboard.php?page=reportes">Reportes</a></li>
                <?php endif; ?>
                <?php if ($role == 2): // Repartidor ?>
                    <!-- Opciones específicas para Repartidor -->
                <?php endif; ?>
                <?php if ($role == 3): // Usuario ?>
                    <!-- Opciones específicas para Usuario -->
                <?php endif; ?>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Bienvenido, <?php echo htmlspecialchars($username); ?></h1>
            <a href="logout.php">Cerrar sesión</a>

            <!-- Contenido dinámico -->
            <?php
             
            $page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

            switch ($page) {
                case 'envios':
                    echo "<h2>Envios</h2>";
                    // lógica para la gestión de envíos
                    break;
                case 'seguimiento':
                    echo "<h2>Seguimiento</h2>";
                    //  para el seguimiento de envíos
                    break;
                case 'rastreo':
                    echo "<h2>Número de Rastreo</h2>";
                    // lógica para el rastreo
                    break;
                case 'usuarios':
                    echo "<h2>Gestión de Usuarios</h2>";
                    // lógica para la gestión de usuarios
                    break;
                case 'reportes':
                    echo "<h2>Reportes</h2>";
                    //  lógica para la generación de reportes
                    break;
                default:
                    echo "<h2>Panel Principal</h2>";
                    echo "<p>Selecciona una opción del menú para comenzar.</p>";
                    break;
            }
            ?>
        </div>
    </div>

    <!-- Formulario de actualización de perfil -->
    <div class="modal" id="update-profile-modal">
        <form id="update-profile-form" class="form" method="POST">
            <h3>Actualizar Perfil</h3>
            <?php if ($profile_update_message): ?>
                <p class="form-message <?php echo strpos($profile_update_message, 'Error') === false ? 'success-message' : ''; ?>">
                    <?php echo $profile_update_message; ?>
                </p>
            <?php endif; ?>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
            <input type="submit" name="update_profile" value="Actualizar Perfil">
        </form>
    </div>

    <!-- Formulario de cambio de contraseña -->
    <div class="modal" id="change-password-modal">
        <form id="change-password-form" class="form" method="POST">
            <h3>Cambiar Contraseña</h3>
            <?php if ($password_change_message): ?>
                <p class="form-message <?php echo strpos($password_change_message, 'Error') === false ? 'success-message' : ''; ?>">
                    <?php echo $password_change_message; ?>
                </p>
            <?php endif; ?>
            <label for="current_password">Contraseña Actual:</label>
            <input type="password" id="current_password" name="current_password" required><br><br>
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required><br><br>
            <input type="submit" name="change_password" value="Cambiar Contraseña">
        </form>
    </div>