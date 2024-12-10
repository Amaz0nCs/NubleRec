<?php
include 'config.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Consulta para obtener los datos de la tabla Campana_Reciclaje
$sql = "SELECT ID_campana, Nombre_campana, Descripcion, Fecha_RegistroCampana, Fecha_inicio, Fecha_termino, Direccion, latitud, longitud FROM Campana_Reciclaje";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error en la consulta SQL: " . $conn->error]);
    exit;
}

$campanas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $campanas[] = [
            'ID_campana' => (int)$row['ID_campana'],
            'Nombre_campana' => $row['Nombre_campana'],
            'Descripcion' => $row['Descripcion'],
            'Fecha_RegistroCampana' => $row['Fecha_RegistroCampana'],
            'Fecha_inicio' => $row['Fecha_inicio'],
            'Fecha_termino' => $row['Fecha_termino'],
            'Direccion' => $row['Direccion'],
            'latitud' => number_format((float)$row['latitud'], 6, '.', ''),
            'longitud' => number_format((float)$row['longitud'], 6, '.', '')
        ];
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($campanas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["message" => "No se encontraron campañas de reciclaje"]);
}

$conn->close();
?>
