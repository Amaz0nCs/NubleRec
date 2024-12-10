<?php
include 'conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL (igual que antes)
$sql = "SELECT 
            Puntos_reciclajes.ID_punto, 
            Puntos_reciclajes.nombre, 
            Puntos_reciclajes.direccion, 
            Puntos_reciclajes.latitud, 
            Puntos_reciclajes.longitud, 
            Ciudad.Nombre_Ciudad, 
            GROUP_CONCAT(CategoriasReciclajes.Nombre_categoria SEPARATOR ', ') AS Nombre_categorias
        FROM 
            Puntos_reciclajes
        INNER JOIN 
            Ciudad ON Puntos_reciclajes.ID_ciudad = Ciudad.ID_ciudad
        LEFT JOIN 
            CategoriasReciclajes ON CategoriasReciclajes.ID_categoria = Puntos_reciclajes.ID_categoria
        GROUP BY 
            Puntos_reciclajes.ID_punto";

$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Crear la tabla HTML
    echo "<table border='1'>
            <tr>
                <th>ID Punto</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Latitud</th>
                <th>Longitud</th>
                <th>Ciudad</th>
                <th>Categorías</th>
                <th>Acciones</th>
            </tr>";

    // Mostrar los resultados en la tabla
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["ID_punto"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["direccion"] . "</td>
                <td>" . $row["latitud"] . "</td>
                <td>" . $row["longitud"] . "</td>
                <td>" . $row["Nombre_Ciudad"] . "</td>
                <td>" . $row["Nombre_categorias"] . "</td>
                <td>
                   <button onclick=\"editRecord(" . $row["ID_punto"] . ")\"> ✏️ </button>
                    <button onclick=\"deleteRecord(" . $row["ID_punto"] . ")\"> 🗑️ </button>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}

$conn->close();
?>
