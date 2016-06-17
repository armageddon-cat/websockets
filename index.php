<?php
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 16.06.2016
 * Time: 15:24
 */
include_once __DIR__ . DIRECTORY_SEPARATOR . 'WebSocket.php';

$socket = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$connects = array();
while (true) {
    //формируем массив прослушиваемых сокетов:
    $read = $connects;
    $read[] = $socket;
    $write = $except = null;
    
    if (!stream_select($read, $write, $except, null)) {//ожидаем сокеты доступные для чтения (без таймаута)
        break;
    }
    
    if (in_array($socket, $read)) {//есть новое соединение
        $connect = stream_socket_accept($socket, -1);//принимаем новое соединение
        $connects[] = $connect;//добавляем его в список необходимых для обработки
        unset($read[ array_search($socket, $read) ]);
    }
    
    foreach($read as $connect) {//обрабатываем все соединения
        //...обрабатываем $connect
        unset($connects[ array_search($connect, $connects) ]);
    }
}