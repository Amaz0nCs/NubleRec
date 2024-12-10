<?php
// Conexión a la base de datos
include('conexion.php');

// Verificar si se enviaron los datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Vincular los parámetros
        $stmt->bind_param("ssssii", $nombre, $direccion, $latitud, $longitud, $id_ciudad, $id_categoria);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Punto de reciclaje agregado correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al agregar el punto de reciclaje: ' . $stmt->error]);
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    }
}

// Cerrar la conexión
$conn->close();
?>
