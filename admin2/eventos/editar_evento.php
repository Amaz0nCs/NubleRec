<?php
// Incluir archivo de conexión a la base de datos
include('conexion.php');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $id_campana = $_POST['id_campana'];  // El ID de campana es pasado como un campo oculto
    $nombre_campana = $_POST['nombre_campana'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_termino = $_POST['fecha_termino'];
    $direccion = $_POST['direccion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $id_ciudad = $_POST['id_ciudad'];

    // Validación básica
    if (!empty($nombre_campana) && !empty($descripcion) && !empty($fecha_inicio) && !empty($fecha_termino) && !empty($direccion) && !empty($latitud) && !empty($longitud) && !empty($id_ciudad)) {
        // Crear la consulta SQL para actualizar los datos
        $sql = "UPDATE Campana_Reciclaje SET 
                nombre_campana = ?, 
                descripcion = ?, 
                fecha_inicio = ?, 
                fecha_termino = ?, 
                direccion = ?, 
                latitud = ?, 
                longitud = ?, 
                id_ciudad = ? 
                WHERE id_campana = ?";

        // Preparar la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular los parámetros
            $stmt->bind_param("ssssssssi", $nombre_campana, $descripcion, $fecha_inicio, $fecha_termino, $direccion, $latitud, $longitud, $id_ciudad, $id_campana);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Campana actualizada exitosamente.";
            } else {
                echo "Error al actualizar la campana: " . $stmt->error;
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

// Cerrar la conexión
$conn->close();
?>
