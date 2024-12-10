<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Gestión de Campañas</title>
    <meta property="og:title" content="Gestión de Puntos">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="adm_eventos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <link rel="stylesheet" href="EstiloAdmEvent.css">
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
      <link href="./EstiloAdmEvent.css" rel="stylesheet">
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
                  <a href="reg_evnts.php" class="edicion-puntos-navlink">Eventos</a>
                  <a href="../index.php" class="edicion-puntos-navlink">Puntos</a>
                </nav>
              </div>
            </div>
          </div>
        </header>
          <!-- Contenedor del mensaje emergente -->
          <div id="messageContainer" class="message-container"></div>

<div class="puntos-wrapper">
    <h2>Gestión de Puntos</h2>
    <button id="openFormBtn">Agregar Campaña de Reciclaje</button>

    <!-- Formulario de Agregar -->
    <div class="form-container" id="formContainer" style="display: none;">
        <form id="addForm" method="POST" action="agregar_evento.php">
            <h1>Añadir Nueva Campaña de Reciclaje</h1>

            <label for="nombre_campana">Nombre de la Campaña:</label>
            <input type="text" id="nombre_campana" name="nombre_campana" required><br><br>
    
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea><br><br>
    
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required><br><br>
    
            <label for="fecha_termino">Fecha de Término:</label>
            <input type="date" id="fecha_termino" name="fecha_termino" required><br><br>
    
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required><br><br>
    
            <label for="latitud">Latitud:</label>
            <input type="text" id="latitud" name="latitud"><br><br>
    
            <label for="longitud">Longitud:</label>
            <input type="text" id="longitud" name="longitud"><br><br>
    
            <label for="ciudad">Ciudad:</label>
            <select id="ciudad" name="id_ciudad" required>
                <option value="1" selected>Ciudad por defecto</option>
            </select><br><br>
    
            <button type="submit">Guardar Campaña</button>
            <button type="button" class="close-btn" id="closeFormBtn">Cerrar</button>
        </form>
    </div>

    <!-- Formulario de Editar -->
    <div class="form-container" id="editFormContainer" style="display: none;">
        <form id="editForm" method="POST" action="editar_evento.php">
            <h1>Editar Campaña de Reciclaje</h1>
            <input type="hidden" name="id_campana" id="editID_campana" />
        
            <label for="editNombreCampana">Nombre de la Campaña:</label>
            <input type="text" name="nombre_campana" id="editNombreCampana" required />
        
            <label for="editDescripcion">Descripción:</label>
            <textarea name="descripcion" id="editDescripcion" required></textarea>
        
            <label for="editFechaInicio">Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" id="editFechaInicio" required />
        
            <label for="editFechaTermino">Fecha de Término:</label>
            <input type="date" name="fecha_termino" id="editFechaTermino" required />
        
            <label for="editDireccion">Dirección:</label>
            <input type="text" name="direccion" id="editDireccion" required />
        
            <label for="editLatitud">Latitud:</label>
            <input type="text" name="latitud" id="editLatitud" required />
        
            <label for="editLongitud">Longitud:</label>
            <input type="text" name="longitud" id="editLongitud" required />
        
            <label for="editCiudad">Ciudad:</label>
            <input type="text" name="id_ciudad" id="editCiudad" required />
        
            <button type="submit">Actualizar</button>
            <button type="button" class="close-btn" id="closeEditFormBtn">Cerrar</button>
        </form>
    </div>

    <button id="printBtn">Imprimir Tabla en PDF</button>

    <!-- Tabla de datos -->
    <div id="dataGrid">Cargando datos...</div>
</div>
<script>
        // Función para mostrar mensajes emergentes
        function showMessage(text, isSuccess = true) {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.textContent = text;
            messageContainer.style.backgroundColor = isSuccess ? '#4caf50' : '#f44336';
            messageContainer.style.display = 'block';
            setTimeout(() => {
                messageContainer.style.display = 'none';
            }, 3000);
        }

        // Abrir formulario de agregar
        document.getElementById('openFormBtn').addEventListener('click', () => {
            document.getElementById('formContainer').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Cerrar formulario de agregar
        document.getElementById('closeFormBtn').addEventListener('click', () => {
            document.getElementById('formContainer').style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Cerrar formulario de editar
        document.getElementById('closeEditFormBtn').addEventListener('click', () => {
            document.getElementById('editFormContainer').style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Imprimir tabla en PDF
        document.getElementById('printBtn').addEventListener('click', () => {
            const element = document.getElementById('dataGrid');
            const options = {
                filename: 'tabla_puntos_reciclaje.pdf',
                margin: 10,
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            };
            html2pdf().from(element).set(options).save();
        });

        // Manejo del formulario de agregar
        document.getElementById('addForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(document.getElementById('addForm'));

            fetch('agregar_evento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showMessage('Campaña agregada con éxito');
                document.getElementById('formContainer').style.display = 'none';
                document.body.style.overflow = 'auto';
                loadDataGrid(); // Recargar la tabla
            })
            .catch(error => {
                console.error('Error al agregar la campaña:', error);
                showMessage('Hubo un error al agregar la campaña.', false);
            });
        });

        // Manejo del formulario de editar
        document.getElementById('editForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(document.getElementById('editForm'));

            fetch('editar_evento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showMessage('Campaña editada con éxito');
                document.getElementById('editFormContainer').style.display = 'none';
                document.body.style.overflow = 'auto';
                loadDataGrid(); // Recargar la tabla
            })
            .catch(error => {
                console.error('Error al editar la campaña:', error);
                showMessage('Hubo un error al editar la campaña.', false);
            });
        });

        // Función para cargar la tabla dinámicamente
      

        // Llamar a la función para cargar la tabla al inicio
        loadDataGrid();
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
    <script defer="" src="https://unpkg.com/@teleporthq/teleport-custom-scripts">
    </script>
  </body>
</html>