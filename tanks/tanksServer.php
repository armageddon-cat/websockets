<?php
declare(strict_types=1);
namespace Tanks;
use Validators\GuidValidator;
use WebSocket\WebSocket;
require __DIR__ . '/../vendor/autoload.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$socket = stream_socket_server('tcp://185.154.13.92:8124', $errno, $errstr);
if (!$socket) {
    die("$errstr ($errno)\n");
}
$connects = array();
$i=0;
while (true) {
    //формируем массив прослушиваемых сокетов:
    $read   = $connects;
    $read[] = $socket;
    $write  = $except = null;
    
    if (!stream_select($read, $write, $except, 0, 100000)) {//ожидаем сокеты доступные для чтения (без таймаута)
//        var_dump('lamda'.$i++);
       // break;
    }
    
    if (in_array($socket, $read)) {//есть новое соединение
        //принимаем новое соединение и производим рукопожатие:
        if (($connect = stream_socket_accept($socket, -1))) {
            /** @var resource $connect */
            $ws = new WebSocket($connect);
            $info = $ws->handshake();
            
            if ($info) {
                $connects[] = $connect;//добавляем его в список необходимых для обработки
                onOpen($connect);//вызываем пользовательский сценарий
                //  $data = fread($connect, 100000); instead of info to function
            }
        }
        unset($read[array_search($socket, $read)]);//далее убираем сокет из списка доступных для чтения
    }
    if (!empty($read)) {
        $serverTime = \DateTime::createFromFormat('U.u', microtime(true)); // create time with microseconds
        foreach($read as $currentConnect) {//обрабатываем все соединения
            $data = fread($currentConnect, 100000);
            if (!strlen($data)) { //соединение было закрыто
                fclose($currentConnect);
                unset($connects[array_search($currentConnect, $connects)]);
                onClose($currentConnect);//вызываем пользовательский сценарий
                continue;
            }
        
            onMessage($currentConnect, $data, $serverTime);//вызываем пользовательский сценарий
        }
        usleep(200000);
    } else {
        $k=0;
        foreach($connects as $Cconnect) {//обрабатываем все соединения
            $storage = TankRegistry::getStorageJSON();
            $encmessage = WebSocket::encode($storage);
//            fwrite($Cconnect, WebSocket::encode('hello'.$k++));
            fwrite($Cconnect, $encmessage);
        }
        usleep(200000);
    }
//    fwrite($connect, WebSocket::encode('hello'));
    
//    var_dump('test'.$i++);
    
}

fclose($socket);

/**
 * @param resource $connect
 * @param $b
 */
function onOpen($connect) {
    var_dump('connection opened');
    $now = \DateTime::createFromFormat('U.u', microtime(true)); // create time with microseconds
    $tank = new Tank($now);
    TankRegistry::addTank($tank);
    $dataObject = (string)$tank;
    fwrite($connect,  WebSocket::encode($dataObject));
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
function onMessage($connect, $data, $serverTime) {
    //var_dump('Someone Came');
    $decmessage = WebSocket::decode($data);
//    var_dump('$decmessage');
//    var_dump($decmessage);
    $dataObject = json_decode($decmessage['payload']);
    if ($dataObject === null) {
        var_dump('wrong data');
        var_dump(json_last_error_msg());
        return true;
    }
    if (empty($dataObject->id) || !GuidValidator::validate($dataObject->id)) {
        var_dump('tank id not defined');
        return true;
    }
    if (!TankRegistry::checkTank($dataObject->id)) {
        var_dump('tank does not exist');
        return true;
    }
    if (isset($dataObject->type) && $dataObject->type === 'bullet') {
        if (!BulletRegistry::checkBullet($dataObject->id)) {
            $bullet = new Bullet($dataObject);
            BulletRegistry::addBullet($bullet);
            $tankBullet = TankRegistry::getTank($dataObject->id);
            $tankBullet->setBullet($bullet);
        }
    }
    $tank = TankRegistry::getTank($dataObject->id);
    if (!empty($dataObject->newd)) {
        $tank->setDirection($dataObject->newd); // todo refactor
        $clientTime = \DateTime::createFromFormat('U.u', str_replace(',', '.', $dataObject->time/1000));
        $interval = $clientTime->diff($serverTime);
        $time = $serverTime;
        if ((int)$interval->format('%Y%m%d%H%m%s') === 0) { // if difference more than 1 second use server time
            $time = $clientTime;
        }
        $tank->setTime($time);
        $tank->moveTank(); // todo refactor
    }
    $bullets = BulletRegistry::getStorage();
    if (count($bullets) !== 0) {
        /** @var Bullet $bullet */
        foreach ($bullets as $bullet) {
            $bullet->checkIntersection();
            BulletRegistry::removeBullet($bullet->getId());
        }
    }
    $storage = TankRegistry::getStorageJSON();
    // after shooting and checking intesection. save tank state. and now we can unset bullets
    if (count($bullets) !== 0) {
        TankRegistry::unsetBullets();
        BulletRegistry::unsetStorage();
    }
    BulletRegistry::unsetStorage();
//    $storage1 = TankRegistry::getStorageJSON();
//    var_dump('$storage1');
//    var_dump($storage1);
    $encmessage = WebSocket::encode($storage);
    fwrite($connect, $encmessage);
}