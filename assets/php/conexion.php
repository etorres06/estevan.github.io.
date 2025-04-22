<?php
$host = "localhost";
$usuario = "root"; // o el nombre de tu usuario
$contrasena = "";  // tu contraseña, si tienes
$bd = "valcucini";

$conexion = new mysqli($host, $usuario, $contrasena, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
