<?php
// Configuración de conexión (ajusta con tus datos)
$host = 'localhost';
$dbname = 'valcucini';
$user = 'root';
$pass = ''; // contraseña

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar los datos
    $estrellas = isset($_POST['estrellas']) ? (int)$_POST['estrellas'] : 0;
    $opinion = isset($_POST['opinion']) ? trim($_POST['opinion']) : '';
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

    // Validar campos
    if ($estrellas < 1 || $estrellas > 5 || empty($opinion) || empty($titulo) || empty($nombre)) {
        echo "Todos los campos son obligatorios y la puntuación debe ser entre 1 y 5.";
        exit;
    }

    try {
        // Preparar consulta SQL
        $sql = "INSERT INTO resenas (nombre, titulo, opinion, estrellas, fecha) 
                VALUES (:nombre, :titulo, :opinion, :estrellas, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':opinion', $opinion);
        $stmt->bindParam(':estrellas', $estrellas);

        // Ejecutar y confirmar
        if ($stmt->execute()) {
            echo "<h3>¡Gracias por tu reseña, $nombre!</h3>";
            echo "<strong>Estrellas:</strong> $estrellas ⭐<br>";
            echo "<strong>Título:</strong> " . htmlspecialchars($titulo) . "<br>";
            echo "<strong>Opinión:</strong> " . nl2br(htmlspecialchars($opinion)) . "<br>";
        } else {
            echo "Error al guardar tu reseña. Intenta nuevamente.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "Acceso no permitido.";
}
?>
