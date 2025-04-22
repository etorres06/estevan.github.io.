<?php
// Validar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $estrellas = isset($_POST['estrellas']) ? intval($_POST['estrellas']) : 0;
    $opinion = isset($_POST['opinion']) ? trim($_POST['opinion']) : "";
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : "";
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";

    // Validación simple (puedes mejorarla)
    if ($estrellas > 0 && !empty($opinion) && !empty($titulo) && !empty($nombre)) {
        // Aquí normalmente iría la conexión a base de datos
        // Simulación de éxito
        echo "Reseña recibida correctamente:<br>";
        echo "Estrellas: " . $estrellas . "<br>";
        echo "Título: " . htmlspecialchars($titulo) . "<br>";
        echo "Opinión: " . htmlspecialchars($opinion) . "<br>";
        echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
