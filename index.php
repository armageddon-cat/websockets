<?php
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 16.06.2016
 * Time: 15:24
 */
include_once __DIR__ . DIRECTORY_SEPARATOR . 'WebSocket.php';

$socket = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    while ($conn = stream_socket_accept($socket)) {
        echo 'someone connected';
        $ws = new WebSocket($conn);
        $ws->handshake();
        fwrite($conn, 'Local time ' . date('Y-m-d H:i:s') . "\n");
        fclose($conn);
    }
    fclose($socket);
}