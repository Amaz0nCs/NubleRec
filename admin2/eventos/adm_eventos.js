// Función para abrir el formulario de agregar punto
function openForm() {
    document.getElementById("formContainer").style.display = "block";
}

// Función para manejar el envío del formulario de agregar punto
function submitAddForm(event) {
    event.preventDefault();

    const formData = new FormData(document.getElementById('addForm'));

    fetch('agregar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Mensaje de éxito o error del servidor
        document.getElementById('addForm').reset(); // Limpiar el formulario
        toggleAddForm(); // Cerrar el formulario de agregar
        loadDataGrid();  // Recargar el DataGrid
    })
    .catch(error => console.error('Error al agregar el punto:', error));
}

// Función para cargar opciones dinámicas (ciudades y categorías)
async function loadEditFormOptions() {
    try {
        // Cargar opciones de ciudades
        const ciudadResponse = await fetch('get_ciudades.php');
        const ciudades = await ciudadResponse.json();
        const ciudadSelect = document.getElementById('editCiudad');
        ciudadSelect.innerHTML = ''; // Limpia las opciones anteriores
        ciudades.forEach(ciudad => {
            const option = document.createElement('option');
            option.value = ciudad.id;
            option.textContent = ciudad.nombre;
            ciudadSelect.appendChild(option);
        });

        // Cargar opciones de categorías
        const categoriaResponse = await fetch('get_categorias.php');
        const categorias = await categoriaResponse.json();
        const categoriaSelect = document.getElementById('editCategoria');
        categoriaSelect.innerHTML = ''; // Limpia las opciones anteriores
        categorias.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id;
            option.textContent = categoria.nombre;
            categoriaSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error al cargar opciones dinámicas:', error);
    }
}

// FUNCIONES PARA EDITAR EL FORMULARIO (Abrir, Cerrar y editar llenando los campos desde un botón)
function openEditForm() {
    document.getElementById('formContainer').style.display = 'flex';
}

function closeEditForm() {
    document.getElementById('formContainer').style.display = 'none';
}

// Función para editar un registro
async function editRecord(ID_campana) {
    try {
        // Hacer la solicitud para obtener los datos de la campaña
        const response = await fetch(`get_campana.php?ID_campana=${ID_campana}`);

        // Verificar si la respuesta fue exitosa
        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.statusText}`);
        }

        // Intentar analizar la respuesta JSON
        const data = await response.json();

        // Verificar que los datos no sean nulos o inválidos
        if (!data || !data.id) {
            console.error('Datos no encontrados o error en la respuesta');
            alert('No se pudieron cargar los datos.');
            return;
        }

        // Llamar a openEditForm con los datos obtenidos
        openEditForm(data);

    } catch (error) {
        console.error('Error al cargar los datos:', error);
        alert('Hubo un error al cargar los datos. Verifique la consola para más detalles.');
    }
}

// Función para abrir el formulario de edición y llenar los campos
function openEditForm(data) {
    if (data) {
        document.getElementById('editID_campana').value = data.id || '';
        document.getElementById('editNombreCampana').value = data.nombre_campana || '';
        document.getElementById('editDescripcion').value = data.descripcion || '';
        document.getElementById('editFechaInicio').value = data.fecha_inicio || '';
        document.getElementById('editFechaTermino').value = data.fecha_termino || '';
        document.getElementById('editDireccion').value = data.direccion || '';
        document.getElementById('editLatitud').value = data.latitud || '';
        document.getElementById('editLongitud').value = data.longitud || '';
        document.getElementById('editCiudad').value = data.id_ciudad || '';

        // Mostrar el formulario de edición
        document.getElementById('editFormContainer').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Desactiva el scroll de la página
    } else {
        console.error('Los datos de la campaña son inválidos');
    }
}

// Función para cerrar el formulario de edición
document.getElementById('closeButton').onclick = function() {
    closeEditForm();
    document.body.style.overflow = 'auto'; // Reactiva el scroll de la página
};

// Función para eliminar un registro
async function deleteRecord(ID_campana) {
    const confirmDelete = confirm('¿Estás seguro de que deseas eliminar esta campaña?');
    if (!confirmDelete) return; // Si el usuario cancela, no hacemos nada

    try {
        const response = await fetch('eliminar_evento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'ID_campana': ID_campana
            })
        });

        const result = await response.json(); // Asumimos que el servidor responde con JSON

        if (result.success) {
            alert('Campaña eliminada exitosamente.');
            loadDataGrid(); // Recargar la tabla
        } else {
            alert('Error al eliminar la campaña: ' + result.error);
        }
    } catch (error) {
        console.error('Error al eliminar el registro:', error);
        alert('Hubo un error al intentar eliminar la campaña.');
    }
}

// Función para cargar la tabla dinámicamente
async function loadDataGrid() {
    try {
        const response = await fetch('ver_datos_eventos.php');
        const data = await response.text();
        document.getElementById('dataGrid').innerHTML = data;
    } catch (error) {
        console.error('Error al cargar la tabla:', error);
        document.getElementById('dataGrid').innerHTML = "Error al cargar los datos.";
    }
}

// Llamar a la función para cargar la tabla al inicio
loadDataGrid();

// Función para generar el PDF de la tabla
document.getElementById('printBtn').addEventListener('click', async function() {
    await loadDataGrid(); // Asegurarse de que la tabla esté completamente cargada
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.autoTable({ html: '#dataGrid table' }); // Asegúrate de que el selector sea correcto
    doc.save('puntos_reciclaje.pdf');
});

// Función para mostrar un mensaje de éxito o error
function showMessage(type, text) {
    const messageDiv = document.getElementById('message');
    messageDiv.style.display = 'block';
    messageDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da'; // Verde para éxito, rojo para error
    messageDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    messageDiv.style.borderColor = type === 'success' ? '#c3e6cb' : '#f5c6cb';
    messageDiv.textContent = text;

    // Ocultar automáticamente después de 3 segundos
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}

// Función para manejar el envío del formulario (agregar/editar)
function handleSubmit(event, actionType) {
    event.preventDefault(); // Prevenir el comportamiento por defecto

    const formData = new FormData(event.target); // Obtener los datos del formulario
    const url = actionType === 'add' ? 'agregar_evento.php' : 'editar_evento.php'; // URL según la acción

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        showMessage('success', actionType === 'add' ? '¡La campaña de reciclaje se ha agregado exitosamente!' : '¡La campaña de reciclaje se ha editado exitosamente!');
        loadDataGrid(); // Recargar los datos
    })
    .catch(error => {
        console.error('Error al enviar los datos:', error);
        showMessage('error', 'Hubo un error al procesar la solicitud.');
    });

    // Evitar el comportamiento por defecto del formulario
    return false;
}
