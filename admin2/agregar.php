<?php
// Conexi車n a la base de datos
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se recibieron los datos del formulario
    if (isset($_POST['nombre'], $_POST['direccion'], $_POST['latitud'], $_POST['longitud'], $_POST['id_ciudad'], $_POST['id_categoria'])) {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];
        $id_ciudad = $_POST['id_ciudad'];
        $id_categoria = $_POST['id_categoria'];

        // Consulta SQL para insertar el nuevo punto de reciclaje
        $sql = "INSERT INTO Puntos_reciclajes (nombre, direccion, latitud, longitud, ID_ciudad, ID_categoria) 
                VALUES (?, ?, ?, ?, ?, ?)";

        // Preparar y ejecutar la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular los par芍metros
            $stmt->bind_param("ssssii", $nombre, $direccion, $latitud, $longitud, $id_ciudad, $id_categoria);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Respuesta JSON si la inserci車n fue exitosa
                echo json_encode(['success' => true, 'message' => 'Punto de reciclaje agregado correctamente.']);
            } else {
                // Respuesta JSON si hubo un error al ejecutar la consulta
                echo json_encode(['error' => 'Error al agregar el punto de reciclaje: ' . $stmt->error]);
            }

            // Cerrar la declaraci車n
            $stmt->close();
        } else {
            // Respuesta JSON si hubo un error al preparar la consulta
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
        }
    } else {
        // Si faltan datos en el formulario, enviar un error
        echo json_encode(['error' => 'Faltan datos en el formulario.']);
    }
}

// Cerrar la conexi車n
$conn->close();
?>
