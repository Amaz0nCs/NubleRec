document.getElementById("login-button").addEventListener("click", function() {
    const email = document.getElementById("thq-sign-in-2-email").value;
    const password = document.getElementById("thq-sign-in-2-password").value;
    const loginButton = document.getElementById("login-button");
    const loadingMessage = document.getElementById("loading-message");
    const errorMessage = document.getElementById("error-message");

    // Limpiar el mensaje de error antes de iniciar el login
    errorMessage.style.display = "none";

    // Validación básica de campos
    if (!email || !password) {
        errorMessage.textContent = "Por favor, complete todos los campos.";
        errorMessage.style.display = "block";
        return;
    }

    loginButton.disabled = true; // Desactivar el botón durante la carga
    loadingMessage.style.display = "block"; // Mostrar mensaje de carga

    // Enviar los datos al archivo PHP para validación
    fetch('validar_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.json())  // Esperar la respuesta en formato JSON
    .then(data => {
        loginButton.disabled = false;  // Habilitar el botón después de la respuesta
        loadingMessage.style.display = "none";  // Ocultar mensaje de carga

        if (data.success) {
            // Si el login es exitoso, redirigir según el tipo de usuario
            if (data.userType === 2) {
                // Redirigir a la página de administrador
                window.location.href = "https://nublerecicla.cl/admin2/index.html";
            } else {
                // Mostrar mensaje de error si el usuario no es administrador
                errorMessage.textContent = "USTED NO ES ADMINISTRADOR";
                errorMessage.style.display = "block";
            }
        } else {
            // Si las credenciales son incorrectas
            errorMessage.textContent = "Credenciales incorrectas. Por favor intente de nuevo.";
            errorMessage.style.display = "block";
        }
    })
    .catch(error => {
        // Manejar errores de la red o del servidor
        loginButton.disabled = false;
        loadingMessage.style.display = "none";
        console.error("Error en la autenticación:", error);
        errorMessage.textContent = "Error al iniciar sesión. Intente nuevamente.";
        errorMessage.style.display = "block";
    });
});
