<?php
// Llamado al archivo que contiene la conexión a la base
include('conexion.php');

// Se revisa si se recibió un id para poder eliminarlo
if (isset($_POST['ID_campana'])) {
    $ID_campana = $_POST['ID_campana'];

    // Consulta SQL para la eliminación del dato
    $sql = "DELETE FROM Campana_Reciclaje WHERE id_campana = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $ID_campana);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la eliminación es exitosa, devolver respuesta JSON positiva
            echo json_encode(['success' => true]);
        } else {
            // Si hubo un error al ejecutar la consulta
            echo json_encode(['error' => 'Error al eliminar la campana: ' . $stmt->error]);
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // En caso de error en la consulta
        echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    }
} else {
    // En caso de no recibir el ID válido
    echo json_encode(['error' => 'ID de la campana no proporcionado']);
}

// Cerrar la conexión
$conn->close();
?>
