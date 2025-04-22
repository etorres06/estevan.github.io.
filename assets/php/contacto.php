<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "127.0.0.1";
$dbname = "valcucini";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => '', 'errors' => []];

    // Sanitizar y validar datos
    $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING));
    $mensaje = trim(filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING));

    // Validaciones
    if (empty($nombre)) {
        $response['errors']['nombre'] = 'El nombre es obligatorio';
    }

    if (empty($email)) {
        $response['errors']['email'] = 'El email es obligatorio';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'El email no es válido';
    }

    if (empty($mensaje)) {
        $response['errors']['mensaje'] = 'El mensaje es obligatorio';
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($response['errors'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO formulario (nombre, email, telefono, mensaje, fecha) 
                                  VALUES (:nombre, :email, :telefono, :mensaje, NOW())");
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':mensaje', $mensaje);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Mensaje enviado con éxito. Nos pondremos en contacto contigo pronto.';
                
                // Opcional: Enviar email de notificación
                enviarEmailNotificacion($nombre, $email, $telefono, $mensaje);
            }
        } catch(PDOException $e) {
            $response['message'] = 'Error al guardar el mensaje: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Por favor corrige los errores en el formulario';
    }
    
    echo json_encode($response);
}

function enviarEmailNotificacion($nombre, $email, $telefono, $mensaje) {
    $to = "contactenos@cocinasvalcucini.com";
    $subject = "Nuevo mensaje de contacto desde el sitio web";
    

    
    @mail($to, $subject, $message, $headers);
}
?>