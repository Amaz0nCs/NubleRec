<?php
include 'config.php';

// Verificar la conexion
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexion fallida: " . $conn->connect_error]));
}

// Configurar la conexi¨®n para usar UTF-8
$conn->set_charset("utf8");

// Consulta para obtener los puntos de reciclaje con sus categor¨ªas
$sql = "SELECT 
            P.ID_punto, 
            P.nombre, 
            P.direccion, 
            P.latitud, 
            P.longitud, 
            P.ID_ciudad AS ciudad, 
            C.Nombre_categoria AS categoria
        FROM 
            Puntos_reciclajes P
        JOIN 
            CategoriasReciclajes C ON P.ID_categoria = C.ID_categoria";

$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo json_encode(["error" => "Error en la consulta SQL: " . $conn->error]);
    exit;
}

// Crear un arreglo para almacenar los puntos de reciclaje
$puntos = [];

// Verificar si la consulta tiene resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $puntos[] = [
            'ID_punto' => (int)$row['ID_punto'],
            'nombre' => $row['nombre'],
            'direccion' => $row['direccion'],
            'latitud' => $row['latitud'],
            'longitud' => $row['longitud'],
            'ciudad' => $row['ciudad'],
            'categoria' => $row['categoria']
        ];
    }
    // Enviar los datos en formato JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($puntos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["message" => "No se encontraron puntos de reciclaje"]);
}

// Cerrar la conexi¨®n
$conn->close();
