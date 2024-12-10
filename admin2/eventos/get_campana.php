<?php
// Incluye la conexión a la base de datos
include 'conexion.php';

// Obtener el ID de la campana
if (isset($_GET['ID_campana'])) {
    $ID_campana = $_GET['ID_campana'];

    // Consulta para obtener los datos de la campana
    $sql = "SELECT 
                ID_campana, 
                Nombre_campana, 
                Descripcion, 
                Fecha_inicio, 
                Fecha_termino, 
                Direccion, 
                latitud, 
                longitud, 
                id_ciudad
            FROM 
                Campana_Reciclaje 
            WHERE 
                ID_campana = ?";
    
    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $ID_campana);  // Vincular el parámetro
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($ID_campana, $Nombre_campana, $Descripcion, $Fecha_inicio, $Fecha_termino, $Direccion, $latitud, $longitud, $id_ciudad);
        
        // Si hay datos
        if ($stmt->fetch()) {
            // Crear un array con los datos
            $data = array(
                'id' => $ID_campana,
                'nombre' => $Nombre_campana,
                'descripcion' => $Descripcion,
                'fecha_inicio' => $Fecha_inicio,
                'fecha_termino' => $Fecha_termino,
                'direccion' => $Direccion,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'id_ciudad' => $id_ciudad
            );
            // Convertir los datos a formato JSON
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Campana no encontrada']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la consulta']);
    }
} else {
    echo json_encode(['error' => 'ID de campana no proporcionado']);
}

// Cerrar la conexión
$conn->close();
?>