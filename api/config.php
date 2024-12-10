<?php
$servername = "localhost"; // Cambia si tu hosting requiere un servidor diferente
$username = "nublerec_nublerec";
$password = "B3lsaj^vNrL9";
$dbname = "nublerec_Recicla";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
    
}


