<?php
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 16.06.2016
 * Time: 15:24
 */
include_once __DIR__ . DIRECTORY_SEPARATOR . 'WebSocket.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$socket = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
if (!$socket) {
    die("$errstr ($errno)\n");
}
$connects = array();
while (true) {
    //формируем массив прослушиваемых сокетов:
    $read   = $connects;
    $read[] = $socket;
    $write  = $except = null;
    
    if (!stream_select($read, $write, $except, null)) {//ожидаем сокеты доступные для чтения (без таймаута)
        break;
    }
    
    if (in_array($socket, $read)) {//есть новое соединение
        //принимаем новое соединение и производим рукопожатие:
        /** @var resource $connect */
        if (($connect = stream_socket_accept($socket, -1)) && $info = (new WebSocket($connect))->handshake()) {
            $connects[] = $connect;//добавляем его в список необходимых для обработки
            onOpen($connect, $info);//вызываем пользовательский сценарий
        }
        unset($read[array_search($socket, $read)]);//далее убираем сокет из списка доступных для чтения
    }
    
    foreach($read as $connect) {//обрабатываем все соединения
        $data = fread($connect, 100000);
        if (!$data) { //соединение было закрыто
            fclose($connect);
            unset($connects[array_search($connect, $connects)]);
            onClose($connect);//вызываем пользовательский сценарий
            continue;
        }
    
        onMessage($connect, $data);//вызываем пользовательский сценарий
    
    }
}

fclose($socket);

/**
 * @param resource $connect
 * @param $b
 */
function onOpen($connect, $b) {
    var_dump('connection opened');
    fwrite($connect, (new WebSocket($connect))->encode('Привет'));
}

function onClose($a) {
    var_dump('connection lost, sorian');
}

/**
 * @param resource $connect
 * @param $data
 */
function onMessage($connect, $data) {
    var_dump('Someone Came');
    echo (new WebSocket($connect))->decode($data)['payload'] . "\n";
}