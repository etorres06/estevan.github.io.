<?php
// Datos de conexión
$host = "localhost";
$user = "root";
$password = ""; // Tu contraseña si usas XAMPP puede estar vacía
$database = "valcucini"; // Asegúrate de que este nombre esté bien escrito

$conn = new mysqli($host, $user, $password, $database);

// Verifica conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verifica que se usó POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $mensaje = $_POST["mensaje"];

    $sql = "INSERT INTO formulario (nombre, email, telefono, mensaje)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $email, $telefono, $mensaje);

    if ($stmt->execute()) {
        echo "Formulario enviado correctamente.";
    } else {
        echo "Error al guardar los datos.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Si alguien intenta entrar por GET
    http_response_code(405);
    echo "Método no permitido";
}
?>
