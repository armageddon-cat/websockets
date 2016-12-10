<?php
use WebSocket\WebSocket;

include_once __DIR__ . DIRECTORY_SEPARATOR . '../WebSocket.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$socket = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
if (!$socket) {
    die("$errstr ($errno)\n");
}
$connects = array();
while (true) {
    //form array of sockets to listen to:
    $read   = $connects;
    $read[] = $socket;
    $write  = $except = null;
    
    if (!stream_select($read, $write, $except, null)) {//waiting for sockets available for read(without timeout)
        break;
    }
    
    if (in_array($socket, $read)) {//if there is new connection
        //accept new connection and doing the handshake:
        /** @var resource $connect */
        if (($connect = stream_socket_accept($socket, -1)) && $info = (new WebSocket($connect))->handshake()) {
            $connects[] = $connect;//add it to list for work
            onOpen($connect, $info);//call user scenario
        }
        unset($read[array_search($socket, $read)]);//then remove from list available for read
    }
    
    foreach ($read as $connect) {//working out all connections
        $data = fread($connect, 100000);
        if (!$data) { //connection was closed
            fclose($connect);
            unset($connects[array_search($connect, $connects)]);
            onClose($connect);//call user scenario
            continue;
        }

        foreach ($connects as $currentConnect) {
            onMessage($currentConnect, $data);//call user scenario
        }
    }
}

fclose($socket);

/**
 * @internal param resource $connect
 * @internal param $b
 */
function onOpen() {
    var_dump('connection opened');
//    $ws = new WebSocket($connect);
//    fwrite($connect, (new WebSocket($connect))->encode('Hello'));
}

function onClose() {
    var_dump('connection lost, sorian');
}

/**
 * @param resource $connect
 * @param $data
 */
function onMessage($connect, $data) {
    var_dump('Someone Came');
    $ws = new WebSocket($connect);
//    echo (new WebSocket($connect))->decode($data)['payload'] . "\n"; maybe for next versions
    $decmessage = $ws->decode($data);
    $encmessage = $ws->encode($decmessage['payload']);
    fwrite($connect, $encmessage);
}