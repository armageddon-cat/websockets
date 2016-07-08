<?php
namespace Tanks;
use WebSocket\WebSocket;
require __DIR__ . '/../vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 20.06.2016
 * Time: 23:44
 */

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$socket = stream_socket_server('tcp://185.154.13.92:8124', $errno, $errstr);
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
        
        foreach ($connects as $currentConnect) {
            onMessage($currentConnect, $data);//вызываем пользовательский сценарий
        }
    }
}

fclose($socket);

/**
 * @param resource $connect
 * @param $b
 */
function onOpen($connect, $data) {
    var_dump('connection opened');
    $ws = new WebSocket($connect);
    $decmessage = $ws->decode($data);
    var_dump($decmessage['payload']);
    $tankData = json_decode($decmessage['payload']);
    $tank = new Tank($tankData);
    TankRegistry::addTank($tank);
//    $ws = new WebSocket($connect);
//    fwrite($connect, (new WebSocket($connect))->encode('Привет'));
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
    $ws = new WebSocket($connect);
//    echo (new WebSocket($connect))->decode($data)['payload'] . "\n"; может для следующих версий
    $decmessage = $ws->decode($data);
    $tankData = json_decode($decmessage['payload']);
    $tank = new Tank($tankData);
    $tank->moveTank();
    TankRegistry::addTank($tank);
    $encmessage = $ws->encode(TankRegistry::getStorageJSON());
    var_dump(TankRegistry::getStorageJSON());
    fwrite($connect, $encmessage);
}