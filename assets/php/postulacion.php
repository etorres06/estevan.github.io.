<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'valcucini';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $response = ["success" => false, "message" => "", "errors" => []];

    // Sanitización
    $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'registroEmail', FILTER_SANITIZE_EMAIL));
    $cargo = trim(filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_input(INPUT_POST, 'ciudad', FILTER_SANITIZE_STRING));
    $experiencia = trim(filter_input(INPUT_POST, 'experiencia', FILTER_SANITIZE_STRING));
    $herramientas = isset($_POST['herramientas']) ? 1 : 0;
    $transporte = isset($_POST['transporte']) ? 1 : 0;

    // Validaciones
    if (empty($nombre)) $response['errors']['nombre'] = 'El nombre es obligatorio.';
    if (empty($telefono)) $response['errors']['telefono'] = 'El teléfono es obligatorio.';
    if (empty($email)) {
        $response['errors']['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'El correo electrónico no es válido.';
    }
    if (empty($cargo)) $response['errors']['cargo'] = 'El cargo es obligatorio.';
    if (empty($ciudad)) $response['errors']['ciudad'] = 'La ciudad es obligatoria.';
    if (empty($experiencia)) $response['errors']['experiencia'] = 'La experiencia es obligatoria.';

    // Validación de archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != 0) {
        $response['errors']['archivo'] = 'El archivo es obligatorio o hubo un error al subirlo.';
    } else {
        $permitidos = ['pdf', 'jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $permitidos)) {
            $response['errors']['archivo'] = 'Tipo de archivo no permitido. Usa PDF o imágenes.';
        }
    }

    if (!empty($response['errors'])) {
        echo json_encode($response);
        exit;
    }

    // Mover archivo a carpeta segura
    $carpeta = 'uploads/';
    if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

    $nombreArchivo = uniqid('cv_') . '.' . $extension;
    $rutaFinal = $carpeta . $nombreArchivo;

    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaFinal)) {
        $response['message'] = 'Error al guardar el archivo.';
        echo json_encode($response);
        exit;
    }

    // Guardar en base de datos
    try {
        $stmt = $conn->prepare("INSERT INTO postulaciones 
            (nombre_completo, telefono, email, cargo_postulado, ciudad_residencia, experiencia, herramientas, transporte, archivo_cv) 
            VALUES 
            (:nombre, :telefono, :email, :cargo, :ciudad, :experiencia, :herramientas, :transporte, :archivo)");

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':experiencia', $experiencia);
        $stmt->bindParam(':herramientas', $herramientas);
        $stmt->bindParam(':transporte', $transporte);
        $stmt->bindParam(':archivo', $nombreArchivo);

        $stmt->execute();

        $response['success'] = true;
        $response['message'] = '¡Postulación enviada correctamente!';
    } catch (PDOException $e) {
        $response['message'] = 'Error al guardar en la base de datos: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
