<?php
include 'conexion.php';

// Configurar el encabezado para devolver JSON
header('Content-Type: application/json');

$response = []; // Array para la respuesta

try {
    // Verificar si se envió el formulario por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener y validar los datos del formulario
        $nombre_campana = trim($_POST["nombre_campana"] ?? '');
        $descripcion = trim($_POST["descripcion"] ?? '');
        $fecha_inicio = trim($_POST["fecha_inicio"] ?? '');
        $fecha_termino = trim($_POST["fecha_termino"] ?? '');
        $direccion = trim($_POST["direccion"] ?? '');
        $latitud = trim($_POST["latitud"] ?? null);
        $longitud = trim($_POST["longitud"] ?? null);
        $id_ciudad = trim($_POST["id_ciudad"] ?? '');

        // Validar campos obligatorios
        if (empty($nombre_campana) || empty($descripcion) || empty($fecha_inicio) || empty($fecha_termino) || empty($direccion) || empty($id_ciudad)) {
            throw new Exception("Por favor, completa todos los campos requeridos.");
        }

        // Validar formato de las fechas (opcional)
        $fecha_inicio_dt = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        $fecha_termino_dt = DateTime::createFromFormat('Y-m-d', $fecha_termino);

        if (!$fecha_inicio_dt || !$fecha_termino_dt || $fecha_inicio_dt > $fecha_termino_dt) {
            throw new Exception("Las fechas ingresadas no son válidas.");
        }

        // Preparar la consulta SQL con sentencias preparadas
        $sql = "INSERT INTO Campana_Reciclaje (Nombre_campana, Descripcion, Fecha_inicio, Fecha_termino, Direccion, latitud, longitud, ID_ciudad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        // Asignar los valores a los parámetros
        $stmt->bind_param(
            "ssssssdd",
            $nombre_campana,
            $descripcion,
            $fecha_inicio,
            $fecha_termino,
            $direccion,
            $latitud,
            $longitud,
            $id_ciudad
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = [
                "success" => true,
                "message" => "Nueva campaña creada exitosamente."
            ];
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        throw new Exception("Método no permitido.");
    }
} catch (Exception $e) {
    // Capturar errores y enviar un mensaje genérico al cliente
    $response = [
        "success" => false,
        "message" => $e->getMessage()
    ];

    // Registrar el error en el servidor
    error_log("Error en agregar_evento.php: " . $e->getMessage());
}

// Cerrar la conexión
$conn->close();

// Enviar la respuesta en formato JSON
echo json_encode($response);
?>
