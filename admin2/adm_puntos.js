
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
         location.reload();  // Recargar el DataGrid
    })
    .catch(error => console.error('Error al agregar el punto:', error));
}

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

async function editRecord(ID_punto) {
    try {
        // Obtener los datos del punto desde el servidor
        const response = await fetch(`get_punto.php?ID_punto=${ID_punto}`);
        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        // Verifica que los elementos existan antes de asignarles valores
        const ciudadSelect = document.getElementById('editCiudad');
        const categoriaSelect = document.getElementById('editCategoria');

        document.getElementById('editID_punto').value = data.ID_punto;
        document.getElementById('editNombre').value = data.nombre;
        document.getElementById('editDireccion').value = data.direccion;
        document.getElementById('editLatitud').value = data.latitud;
        document.getElementById('editLongitud').value = data.longitud;

        if (ciudadSelect) ciudadSelect.value = data.id_ciudad;
        if (categoriaSelect) categoriaSelect.value = data.id_categoria;

        // Mostrar el formulario de edición
        document.getElementById('editFormModal').style.display = "block";

    } catch (error) {
        console.error('Error al cargar los datos:', error);
        alert('Hubo un error al cargar los datos.');
    }
}


async function deleteRecord(ID_punto) {
    // Confirma con el usuario si realmente desea eliminar el registro
    const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este punto?');
    if (!confirmDelete) return; // Si el usuario cancela, no hacemos nada

    try {
        // Hacer una solicitud de eliminación a través de fetch
        const response = await fetch('eliminar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'ID_punto': ID_punto
            })
        });

        const result = await response.json(); // Asumimos que el servidor responde con JSON

        if (result.success) {
            alert('Punto eliminado exitosamente.');
            // Aquí podrías actualizar la lista de puntos o recargar la página
            location.reload(); // Recarga la página para ver los cambios
        } else {
            alert('Error al eliminar el punto: ' + result.error);
        }

    } catch (error) {
        console.error('Error al eliminar el registro:', error);
        alert('Hubo un error al intentar eliminar el registro.');
    }
}

function closeEditForm() {
    document.getElementById("editFormModal").style.display = "none";
}



function confirmDelete(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
        window.location.href = 'eliminar.php?id=' + id;
    }
}

  // Función para cargar la tabla dinámicamente
  async function loadDataGrid() {
    try {
        const response = await fetch('ver_datos.php');
        const data = await response.text();
        document.getElementById('dataGrid').innerHTML = data;
    } catch (error) {
        console.error('Error al cargar la tabla:', error);
        document.getElementById('dataGrid').innerHTML = "Error al cargar los datos.";
    }
}



// Llamar a la función para cargar la tabla
loadDataGrid();

// Función para generar el PDF de la tabla
document.getElementById('printBtn').addEventListener('click', async function() {
    // Esperamos a que la tabla esté completamente cargada antes de generar el PDF
    await loadDataGrid();

    // Ahora generamos el PDF con jsPDF y el plugin autoTable
    const { jsPDF } = window.jspdf;  // Usamos jsPDF desde el espacio global
    const doc = new jsPDF();

    // Usamos el plugin autoTable para convertir la tabla HTML a PDF
    doc.autoTable({ html: '#dataGrid table' });  // Asegúrate de que el selector sea correcto

    // Generamos y descargamos el PDF
    doc.save('puntos_reciclaje.pdf');
});

// Llama a la función cuando la página se carga
window.onload = loadDataGrid;