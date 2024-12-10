<?php
include 'conexion.php'; // Asegúrate de que la ruta sea correcta

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configurar la conexión para usar utf8mb4
$conn->set_charset("utf8mb4");

// Consulta SQL
$sql = "SELECT 
            ID_campana, 
            Nombre_campana, 
            Descripcion, 
            Fecha_inicio, 
            Fecha_termino, 
            Direccion, 
            latitud, 
            longitud, 
            Nombre_Ciudad
        FROM 
            Campana_Reciclaje
        INNER JOIN 
            Ciudad ON Campana_Reciclaje.ID_ciudad = Ciudad.ID_ciudad";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Crear la tabla HTML con clases para el estilo
    echo "<table class='campaña-table'>
            <thead>
                <tr>
                    <th>ID Campana</th>
                    <th>Nombre Campana</th>
                    <th>Descripción</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Término</th>
                    <th>Dirección</th>
                    <th>Latitud</th>
                    <th>Longitud</th>
                    <th>Ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    // Mostrar los resultados en la tabla
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["ID_campana"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Nombre_campana"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Descripcion"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Fecha_inicio"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Fecha_termino"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Direccion"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["latitud"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["longitud"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row["Nombre_Ciudad"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>
                    <button onclick=\"editRecord(" . htmlspecialchars($row["ID_campana"], ENT_QUOTES, 'UTF-8') . ")\"> ✏️ </button>
                    <button onclick=\"deleteRecord(" . htmlspecialchars($row["ID_campana"], ENT_QUOTES, 'UTF-8') . ")\"> 🗑️ </button>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "0 resultados";
}
?>
