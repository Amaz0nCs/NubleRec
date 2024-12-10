<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: https://nublerecicla.cl/iniciar-sesion.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en"><head>
    <title>Gestión de Puntos</title>
    <meta property="og:title" content="Gestión de Puntos">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="adm_puntos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <link rel="stylesheet" href="EstiloAdm.css">
    <style data-tag="reset-style-sheet">
      html {  line-height: 1.15;}body {  margin: 0;}* {  box-sizing: border-box;  border-width: 0;  border-style: solid;  -webkit-font-smoothing: antialiased;}p,li,ul,pre,div,h1,h2,h3,h4,h5,h6,figure,blockquote,figcaption {  margin: 0;  padding: 0;}button {  background-color: transparent;}button,input,optgroup,select,textarea {  font-family: inherit;  font-size: 100%;  line-height: 1.15;  margin: 0;}button,select {  text-transform: none;}button,[type="button"],[type="reset"],[type="submit"] {  -webkit-appearance: button;  color: inherit;}button::-moz-focus-inner,[type="button"]::-moz-focus-inner,[type="reset"]::-moz-focus-inner,[type="submit"]::-moz-focus-inner {  border-style: none;  padding: 0;}button:-moz-focus,[type="button"]:-moz-focus,[type="reset"]:-moz-focus,[type="submit"]:-moz-focus {  outline: 1px dotted ButtonText;}a {  color: inherit;  text-decoration: inherit;}input {  padding: 2px 4px;}img {  display: block;}html { scroll-behavior: smooth  }
    </style>
    <style>
        /* Estilo para el mensaje emergente */
        #message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
        #message.error {
            background-color: #f44336;
        }
    </style>
    <style data-tag="default-style-sheet">
      html {
        font-family: Titillium Web;
        font-size: 16px;
      }

      body {
        font-weight: 400;
        font-style:normal;
        text-decoration: none;
        text-transform: none;
        letter-spacing: normal;
        line-height: 1.15;
        color: var(--dl-color-scheme-green100);
        background: var(--dl-color-scheme-yellow20);

        fill: var(--dl-color-scheme-green100);
      }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/animate.css@4.1.1/animate.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" data-tag="font">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" data-tag="font">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&amp;display=swap" data-tag="font">
    <link rel="stylesheet" href="https://unpkg.com/@teleporthq/teleport-custom-scripts/dist/style.css">
    <!--This is the head section-->
    <!-- <script type="text/javascript"> ... </script> -->
  </head>
  <body>
    <link rel="stylesheet" href="./style.css">
    <div>
      <link href="./EstiloAdm.css" rel="stylesheet">
    </div>
    <div class="edicion-puntos-container">
        <header data-thq="thq-navbar" class="edicion-puntos-navbar-interactive">
          <div class="edicion-puntos-navbar navbar-container">
            <div class="max-width edicion-puntos-max-width">
              <div class="edicion-puntos-logo1">
                <img alt="image" src="public/1.png" class="edicion-puntos-image1">
                 <a href="/index.html"><span>ÑubleRecicla</span></a>
              </div>
              <div data-thq="thq-navbar-nav" class="edicion-puntos-desktop-menu">
                <nav class="edicion-puntos-links1">
                  <a href="index.php" class="boton">Puntos</a>
                  <a href="eventos/reg_evnts.php" class="boton">Eventos</a>
                </nav>
              </div>
            </div>
          </div>
        </header>
       <!-- Contenedor del mensaje emergente -->
<div id="message"></div>

<div class="puntos-wrapper">
    <h2>Gestión de Puntos</h2>
    <button id="openFormBtn">Agregar Punto de Reciclaje</button>

    <!-- Formulario flotante -->
    <div class="form-container" id="formContainer" style="display: none;"> <!-- Ocultar por defecto -->
        <form id="addForm" action="agregar.php" method="POST">
            <h1>Agregar Punto de Reciclaje</h1>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
            <label for="latitud">Latitud:</label>
            <input type="text" id="latitud" name="latitud" required>
            <label for="longitud">Longitud:</label>
            <input type="text" id="longitud" name="longitud" required>
            <label for="ciudad">Ciudad:</label>
            <select id="ciudad" name="id_ciudad" required>
                <option value="1" selected>Ciudad por defecto</option>
            </select>
            <label for="id_categoria">Categoría (ID):</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="1">Papeles Y Cartones</option>
                <option value="2">Plasticos y Pet</option>
                <option value="3">Latas Y Metales</option>
                <option value="4">Vidrios</option>
                <option value="5">Desechos Organicos</option>
                <option value="6">Residuos para Eliminacion</option>
                <option value="7">Residuos Peligrosos</option>
                <option value="8">Carton para Bebidas</option>
                <option value="9">Carton para Bebidas</option>
            </select>
            <button type="submit">Agregar Punto</button>
            <button type="button" class="close-btn" id="closeFormBtn">Cerrar</button>
        </form>
    </div>

    <!-- Botón para imprimir la tabla -->
    <button id="printBtn">Imprimir Tabla en PDF</button>

    <!-- Tabla de datos -->
    <div id="dataGrid">Cargando datos...</div>

    <!-- Formulario de edición -->
    <div id="editFormModal" class="form-container" style="display: none;">
        <form id="editForm" method="POST" action="editar.php">
            <h3>Editar Punto</h3>
            <input type="hidden" id="editID_punto" name="ID_punto">
            <label for="editNombre">Nombre:</label>
            <input type="text" id="editNombre" name="nombre" required>
            <label for="editDireccion">Dirección:</label>
            <input type="text" id="editDireccion" name="direccion" required>
            <label for="editLatitud">Latitud:</label>
            <input type="text" id="editLatitud" name="latitud" required>
            <label for="editLongitud">Longitud:</label>
            <input type="text" id="editLongitud" name="longitud" required>
            <label for="ciudad">Ciudad:</label>
            <select id="ciudad" name="id_ciudad" required>
                <option value="1" selected>Ciudad por defecto</option>
            </select>
            <label for="id_categoria">Categoría (ID):</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="1">Papeles Y Cartones</option>
                <option value="2">Plasticos y Pet</option>
                <option value="3">Latas Y Metales</option>
                <option value="4">Vidrios</option>
                <option value="5">Desechos Organicos</option>
                <option value="6">Residuos para Eliminacion</option>
                <option value="7">Residuos Peligrosos</option>
                <option value="8">Carton para Bebidas</option>
                <option value="9">Carton para Bebidas</option>
            </select>
            <div>
                <button type="submit">Guardar Cambios</button>
                <button type="button" onclick="closeEditForm()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const messageDiv = document.getElementById('message');

    // Función para mostrar mensajes emergentes
    function showMessage(message, type) {
        messageDiv.style.display = 'block';
        messageDiv.style.borderColor = type === 'success' ? 'green' : 'red';
        messageDiv.style.color = type === 'success' ? 'green' : 'red';
        messageDiv.innerText = message;

        // Ocultar el mensaje después de 3 segundos
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // Función para cargar datos (simulada)
    function loadDataGrid() {
        document.getElementById('dataGrid').innerText = 'Datos cargados...';
    }

    // Manejar el formulario de agregar con AJAX
    document.getElementById('addForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir el envío tradicional del formulario
        const formData = new FormData(this);

        // Realizar la solicitud con fetch
        fetch('agregar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Espera una respuesta en JSON
        .then(data => {
            if (data.success) {
                showMessage('Punto de reciclaje agregado con éxito.', 'success');
                document.getElementById('formContainer').style.display = 'none'; // Ocultar el formulario
                 location.reload();
            } else {
                showMessage(data.message || 'Ocurrió un error.', 'error');
            }
        })
        .catch(() => showMessage('Error de red o del servidor.', 'error'));
    });

    // Manejar el formulario de edición con AJAX
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir el envío tradicional del formulario
        const formData = new FormData(this);

        // Realizar la solicitud con fetch
        fetch('editar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage('Punto de reciclaje editado con éxito.', 'success');
                document.getElementById('editFormModal').style.display = 'none'; // Ocultar el formulario
                 location.reload();
            } else {
                showMessage(data.message || 'Ocurrió un error.', 'error');
            }
        })
        .catch(error => showMessage(error.message || 'Error de red o del servidor.', 'error'));
    });

    // Funciones para abrir/cerrar formularios
    const openFormBtn = document.getElementById('openFormBtn');
    const closeFormBtn = document.getElementById('closeFormBtn');
    const formContainer = document.getElementById('formContainer');

    openFormBtn.addEventListener('click', () => {
        formContainer.style.display = 'block'; // Muestra el formulario
    });

    closeFormBtn.addEventListener('click', () => {
        formContainer.style.display = 'none'; // Oculta el formulario
    });

    // Función para cerrar el formulario de edición
    function closeEditForm() {
        document.getElementById('editFormModal').style.display = 'none'; // Ocultar el formulario de edición
    }
</script>

<script defer="">
    window.onload = () => {
        const runAllScripts = () => {
            initializeAllAccordions()
        }

        const listenForUrlChanges = () => {
            let url = location.href
            document.body.addEventListener(
                'click',
                () => {
                    requestAnimationFrame(() => {
                        if (url !== location.href) {
                            runAllScripts()
                            url = location.href
                        }
                    })
                },
                true
            )
        }

        const initializeAllAccordions = () => {
            const allAccordions = document.querySelectorAll('[data-role="Accordion"]');

            allAccordions.forEach((accordion) => {
                const accordionHeader = accordion.querySelector('[data-type="accordion-header"]')
                const accordionContent = accordion.querySelector('[data-type="accordion-content"]')

                accordionHeader.addEventListener('click', () => {
                    if (accordionContent.style.maxHeight) {
                        accordionContent.style.maxHeight = ''
                    } else {
                        accordionContent.style.maxHeight = `${accordionContent.scrollHeight}px`
                    }
                })
            })
        }

        listenForUrlChanges()
        runAllScripts()
    }
</script>
<script defer="" src="https://unpkg.com/@teleporthq/teleport-custom-scripts"></script>
</body>
