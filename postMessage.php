<?php
// Comprobar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar el JSON recibido
    $info = json_decode(file_get_contents('php://input'), true);

    // Verificar si se recibieron el nombre y el mensaje
    if (isset($info['name']) && isset($info['message'])) {
        // Crear el mensaje
        $msg = $info['name'] . ': ' . $info['message'] . PHP_EOL . PHP_EOL;
        
        // Guardar el mensaje en el archivo
        if (file_put_contents('data/messages.txt', $msg, FILE_APPEND | LOCK_EX) !== false) {
            // Devolver una respuesta exitosa
            http_response_code(200);
            echo 'Message saved successfully';
        } else {
            // Devolver un error si falla la escritura
            http_response_code(500);
            echo 'There was an error trying to save the message';
        }
    } else {
        // Devolver un error si faltan datos
        http_response_code(400);
        echo 'There is data missing from the application';
    }
} else {
    // Si la solicitud no es POST, devolver un código de respuesta 405 (Método no permitido)
    http_response_code(405);
}
?>
