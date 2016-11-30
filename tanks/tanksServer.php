<?php
declare(strict_types=1);
namespace Tanks;
use JsonSchema\Exception\JsonDecodingException;
use WebSocket\WebSocket;
require __DIR__ . '/../vendor/autoload.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errno, $errstr);
$socket = stream_socket_server('tcp://185.154.13.92:8124', $errno, $errstr);
if (!$socket) {
    die("$errstr ($errno)\n");
}
$connects = array();
//$i=0;
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
//        var_dump('if');
        $serverTime = DateTimeUser::createDateTimeMicro();
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
//        $k=0;
//        var_dump('else');
        $bullets = BulletRegistry::getStorage();
        foreach ($bullets as $bullet) {
            $bullet->checkIntersection(); // todo check with currect bullet iteration position
//            BulletRegistry::removeBullet($bullet->getId());// todo too early unsetting bullets
        }
        BulletRegistry::moveBullets();
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
 */
function onOpen($connect) {
    var_dump('connection opened');
    $now = DateTimeUser::createDateTimeMicro();
    $tank = new Tank($now);
    TankRegistry::addTank($tank);
    $dataObject = (string)$tank;
    fwrite($connect,  WebSocket::encode($dataObject));
}

function onClose() {
    var_dump('connection lost, sorian');
    // todo in future maybe unset tank here
}

/**
 * everyone send only its data
 *
 * @param resource  $connect
 * @param           $data
 *
 * @param \DateTime $serverTime
 *
 * @return bool
 */
function onMessage($connect, $data, \DateTime $serverTime) {
    //var_dump('Someone Came');
    $decMessage = WebSocket::decode($data);
    if (!$decMessage) {
        var_dump('cannot decode data');
        return true;
    }
//    var_dump('$decMessage');
//    var_dump($decMessage);
    try {
        $message = new ClientMessageContainer($decMessage);
    } catch (EmptyPayLoadException $e) {
        var_dump($e);
        return true;
    } catch (JsonDecodingException $e) {
        var_dump($e);
        return true;
    } catch (InvalidGuidException $e) {
        var_dump($e);
        return true;
    }
    
    if ($message->getType() === ClientMessageContainer::TYPE_BULLET) {
        if (!BulletRegistry::checkBullet($message->getId())) {
            $bullet = new Bullet($message);
            BulletRegistry::addBullet($bullet);
            $tankBullet = TankRegistry::getTank($message->getId());
            $tankBullet->setBullet($bullet);
        }
    }
    $tank = TankRegistry::getTank($message->getId());
    if (!empty($message->getNewd())) {
        $tank->setDirection($message->getNewd());
        $clientTime = $message->getTime();
        $interval = $clientTime->diff($serverTime);
        $time = $serverTime;
        if ((int)$interval->format('%Y%m%d%H%m%s') === 0) { // if difference more than 1 second use server time
            $time = $clientTime;
        }
        
        $tank->moveTank($time); // todo refactor
    }
    $bullets = BulletRegistry::getStorage();
    foreach ($bullets as $bullet) {
        $bullet->checkIntersection(); // todo check with currect bullet iteration position
//            BulletRegistry::removeBullet($bullet->getId());// todo too early unsetting bullets
    }
    BulletRegistry::moveBullets();
    $storage = TankRegistry::getStorageJSON();
    // after shooting and checking intesection. save tank state. and now we can unset bullets
    if (count($bullets) !== 0) {
//        TankRegistry::unsetBullets(); // todo too early unsetting bullets
//        BulletRegistry::unsetStorage();//todo refactor
    }
//    BulletRegistry::unsetStorage();//todo refactor
//    $storage1 = TankRegistry::getStorageJSON();
//    var_dump('$storage1');
//    var_dump($storage1);
    $encmessage = WebSocket::encode($storage);
    fwrite($connect, $encmessage);
}