<?php
include 'conexion.php';

$ID_punto = $_GET['ID_punto'];
$sql = "SELECT * FROM Puntos_reciclajes WHERE ID_punto = '$ID_punto'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Registro no encontrado"]);
}

$conn->close();
?>
