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
    $dataObject = json_decode($decmessage['payload']);
    if ($dataObject === null) {
        var_dump('wrong data');
        var_dump(json_last_error_msg());
        return false;
    }
    if (isset($dataObject->type) && $dataObject->type === 'bullet') {
        if (!BulletRegistry::checkBullet($dataObject->id)) {
            $bullet = new Bullet($dataObject);
            BulletRegistry::addBullet($bullet);
            $tankBullet = TankRegistry::getTank($dataObject->id);
            $tankBullet->setBullet($bullet);
        }
    }
//    var_dump('$decmessage');
//    var_dump($decmessage['payload']);
//    var_dump('$dataObject');
//    var_dump($dataObject);
    if (!TankRegistry::checkTank($dataObject->id)) {
        $tank = new Tank($dataObject);
        TankRegistry::addTank($tank);
    } else {
        $tank = TankRegistry::getTank($dataObject->id);
        if (!empty($dataObject->newd)) {
            $tank->setDirection($dataObject->newd); // todo refactor
        }
    }
    if (!empty($dataObject->newd)) {
        $tank->moveTank(); // todo refactor
    }
    $bullets = BulletRegistry::getStorage();
//    var_dump('$bullets');
//    var_dump($bullets);
    if (count($bullets) !== 0) {
        /** @var Bullet $bullet */
        foreach ($bullets as $bullet) {
            $bullet->checkIntersection();
            BulletRegistry::removeBullet($bullet->getId());
        }
    }
//    var_dump('$tank');
//    var_dump($tank);
    $storage = TankRegistry::getStorageJSON();
    // after shooting and checking intesection. save tank state. and now we can unset bullets
    if (count($bullets) !== 0) {
        TankRegistry::unsetBullets();
        BulletRegistry::unsetStorage();
    }
    BulletRegistry::unsetStorage();
    var_dump('$storage');
    var_dump($storage);
    $storage1 = TankRegistry::getStorageJSON();
    var_dump('$storage1');
    var_dump($storage1);
    $encmessage = WebSocket::encode($storage);
    fwrite($connect, $encmessage);
}