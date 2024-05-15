<?php
$file = 'data/messages.txt';

if (file_exists($file)) {
    $content = file_get_contents($file);
    $content = nl2br($content);
    echo $content;
} else {
    echo 'No messages yet.';
}
?>