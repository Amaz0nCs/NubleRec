<?php
// Incluir archivo de conexión a la base de datos
include('conexion.php');

// Establecer que la respuesta será en formato JSON
header('Content-Type: application/json');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $id_punto = $_POST['ID_punto'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $id_ciudad = $_POST['id_ciudad'];
    $id_categoria = $_POST['id_categoria'];

    // Validación básica
    if (!empty($id_punto) && !empty($nombre) && !empty($direccion) && !empty($latitud) && !empty($longitud) && !empty($id_ciudad) && !empty($id_categoria)) {
        // Crear la consulta SQL para actualizar los datos
        $sql = "UPDATE Puntos_reciclajes SET 
                nombre = ?, 
                direccion = ?, 
                latitud = ?, 
                longitud = ?, 
                id_ciudad = ?, 
                id_categoria = ? 
                WHERE ID_punto = ?";

        // Preparar la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular los parámetros
            $stmt->bind_param("ssssiii", $nombre, $direccion, $latitud, $longitud, $id_ciudad, $id_categoria, $id_punto);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Respuesta exitosa en formato JSON
                echo json_encode([
                    'success' => true,
                    'message' => 'Punto actualizado exitosamente.'
                ]);
            } else {
                // Error al ejecutar la consulta
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el punto: ' . $stmt->error
                ]);
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Error al preparar la consulta
            echo json_encode([
                'success' => false,
                'message' => 'Error al preparar la consulta: ' . $conn->error
            ]);
        }
    } else {
        // Validación fallida: algún campo está vacío
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, complete todos los campos.'
        ]);
    }
} else {
    // Si no es una solicitud POST
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}

// Cerrar la conexión
$conn->close();
?>
