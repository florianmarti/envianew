//SetTimeout Logo y Gif
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
      // Ocultar el loader y el logo
      document.querySelector(".loader-container").classList.add("hidden");
      document.querySelector(".logo-container").classList.add("hidden");
  
      // Mostrar el contenido principal
      document.querySelector(".content").style.display = "block";
    }, 3000);
  });
  
  
document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById("register-form");
    const loginForm = document.getElementById("login-form");
    const showRegisterFormLink = document.getElementById("show-register-form");
    const showLoginFormLink = document.getElementById("show-login-form");

    // Mostrar el formulario de registro o inicio de sesión según el parámetro en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const registered = urlParams.get('registered');

    if (registered === 'true') {
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    } else {
        registerForm.style.display = "block";
        loginForm.style.display = "none";
    }

    showRegisterFormLink.addEventListener("click", function(event) {
        event.preventDefault();
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    });

    showLoginFormLink.addEventListener("click", function(event) {
        event.preventDefault();
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    });
});

// Funciones para manejar modales
document.addEventListener('DOMContentLoaded', () => {
    // Función para mostrar modales
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    // Función para ocultar modales
    function hideModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
    }

    // Manejadores de eventos para abrir los modales
    document.getElementById('update-profile-btn').addEventListener('click', () => {
        showModal('update-profile-modal');
    });

    document.getElementById('change-password-btn').addEventListener('click', () => {
        showModal('change-password-modal');
    });

    // Cerrar modales al hacer clic fuera del contenido del modal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                hideModals();
            }
        });
    });
});
 
