<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $info = json_decode(file_get_contents('php://input'), true);
    if (isset($info['name']) && isset($info['message'])) {
        $msg = $info['name'] . ': ' . $info['message'] . PHP_EOL . PHP_EOL;
        if (file_put_contents('data/messages.txt', $msg, FILE_APPEND | LOCK_EX) !== false) {
            http_response_code(200);
            echo 'Message saved successfully';
        } else {
            http_response_code(500);
            echo 'There was an error trying to save the message';
        }
    } else {

        http_response_code(400);
        echo 'There is data missing from the application';
    }
} else {
    http_response_code(405);
}
?>
