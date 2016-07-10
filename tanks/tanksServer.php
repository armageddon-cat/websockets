<?php
declare(strict_types=1);
namespace Tanks;
use WebSocket\WebSocket;
require __DIR__ . '/../vendor/autoload.php';

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
        if (($connect = stream_socket_accept($socket, -1))) {
            /** @var resource $connect */
            $ws = new WebSocket($connect);
            $info = $ws->handshake();
            if ($info) {
                $connects[] = $connect;//добавляем его в список необходимых для обработки
                onOpen($connect, $info);//вызываем пользовательский сценарий
            }
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
            if (!empty($data)) {
                onMessage($currentConnect, $data);//вызываем пользовательский сценарий
            }
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
    // no operations needed to be here
}

function onClose($a) {
    var_dump('connection lost, sorian');
    // infuture maybe unset tank here
}

/**
 * everyone send only its data
 *
 * @param resource $connect
 * @param          $data
 *
 * @return bool
 */
function onMessage($connect, $data) {
    var_dump('Someone Came');
    $decmessage = WebSocket::decode($data);
    $tankData = json_decode($decmessage['payload']);
    if ($tankData === null) {
        var_dump('wrong data');
        var_dump(json_last_error_msg());
        return false;
    }
    var_dump($decmessage['payload']);
    var_dump($tankData);
    if (!TankRegistry::checkTank($tankData->id)) {
        $tank = new Tank($tankData);
        TankRegistry::addTank($tank);
    } else {
        $tank = TankRegistry::getTank($tankData->id);
        $tank->setDirection($tankData->newd); // todo refactor
    }
    $tank->moveTank();
    var_dump($tank);
    $storage = TankRegistry::getStorageJSON();
    var_dump($storage);
    $encmessage = WebSocket::encode($storage);
    fwrite($connect, $encmessage);
}