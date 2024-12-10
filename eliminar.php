<?php
//Llamado al archivo que contiene la conexion a la base
include('conexion.php');

// Se revisa si se recibio un id par poder eliminarlo
if (isset($_POST['ID_punto'])) {
    $ID_punto = $_POST['ID_punto'];

    // Consulta sql para la eliminacion del dato
    $sql = "DELETE FROM puntos_reciclajes WHERE ID_punto = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $ID_punto);

    
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            // Si hubo un error al ejecutar la consulta
            echo json_encode(['error' => 'Error al eliminar el punto: ' . $stmt->error]);
        }

      
        $stmt->close();
    } else {
        // en caso de un error en la consulta
        echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    }
} else {
    // en caso de no recibir un punto valido
    echo json_encode(['error' => 'ID del punto no proporcionado']);
}

// Cerrar la conexión
$conn->close();
?>