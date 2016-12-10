<?php
declare(strict_types=1);
namespace Tanks;

use Exceptions\EmptyValueException;
use Exceptions\InvalidDateTimeFormatException;
use Exceptions\InvalidGuidException;
use Exceptions\JsonDecodingException;
use WebSocket\WebSocket;

require __DIR__ . '/../vendor/autoload.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errNumber, $errString);
$socket = stream_socket_server('tcp://185.154.13.92:8124', $errNumber, $errString);
if (!$socket) {
    die("$errString ($errNumber)\n");
}
$connects = [];
//$i=0;
while (true) {
    //form array of sockets to listen to:
    $read   = $connects;
    $read[] = $socket;
    $write  = $except = null;
    
    if (!stream_select($read, $write, $except, 0, 100000)) {//waiting for sockets available for read
//        var_dump('lamda'.$i++);
       // break;
    }
    
    if (in_array($socket, $read)) {//if there is new connection
        //accept new connection and doing the handshake:
        if ($connect = stream_socket_accept($socket, -1)) { // todo refactor
            /** @var resource $connect */
            $ws = new WebSocket($connect);
            $info = $ws->handshake();
            
            if ($info) {
                $connects[] = $connect;//add it to list for work
                onOpen($connect);//call user scenario
                //  $data = fread($connect, 100000); instead of info to function
            }
        }
        unset($read[array_search($socket, $read)]);//then remove from list available for read
    }
    if (!empty($read)) {
        //        var_dump('if');
        $serverTime = DateTimeUser::createDateTimeMicro();
        foreach ($read as $currentConnect) {//working out all connections
            $data = fread($currentConnect, 100000);
            if (!strlen($data)) { //connection was closed
                fclose($currentConnect);
                unset($connects[array_search($currentConnect, $connects)]);
                onClose(/*$currentConnect*/);//call user scenario
                continue;
            }
        
            onMessage($currentConnect, $data, $serverTime);//call user scenario
        }
        usleep(200000);
    } else {
        //        $k=0;
//        var_dump('else');
        $bulletsStorage = BulletRegistry::getInstance();
        $bulletsStorage->rewind();
        while ($bulletsStorage->valid()) {
            $bulletsStorage->current()->checkIntersection();
            $bulletsStorage->next();
//          BulletRegistry::removeBullet($bullet->getId());// todo too early unsetting bullets
        }
        BulletRegistry::moveBullets();
        foreach ($connects as $curConnect) {//working out all connections
            $storage = TankRegistry::getStorageJSON();
            $encMessage = WebSocket::encode($storage);
//            fwrite($curConnect, WebSocket::encode('hello'.$k++));
            fwrite($curConnect, $encMessage);
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
function onOpen($connect)
{
    var_dump('connection opened');
    $now = DateTimeUser::createDateTimeMicro();
    $tank = new Tank($now);
    TankRegistry::add($tank);
    $dataObject = (string)$tank;
    fwrite($connect, WebSocket::encode($dataObject));
}

function onClose()
{
    var_dump('connection lost, sorry');
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
function onMessage($connect, $data, \DateTime $serverTime)
{
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
    } catch (EmptyValueException | JsonDecodingException | InvalidGuidException | InvalidDateTimeFormatException $e) {
        var_dump($e);
        return true;
    }
    if ($message->getType() === ClientMessageContainer::TYPE_BULLET) {
        if (!BulletRegistry::exists($message->getId())) {
            $bullet = new Bullet($message);
            BulletRegistry::add($bullet);
            $tankBullet = TankRegistry::get($message->getId());
            $tankBullet->setBullet($bullet);
        }
    }
    $tank = TankRegistry::get($message->getId());
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
    $bulletsStorage = BulletRegistry::getInstance();
    $bulletsStorage->rewind();
    while ($bulletsStorage->valid()) {
        $bulletsStorage->current()->checkIntersection();
        $bulletsStorage->next();
//      BulletRegistry::removeBullet($bullet->getId());// todo too early unsetting bullets
    }
    BulletRegistry::moveBullets();
    $storage = TankRegistry::getStorageJSON();
    // after shooting and checking intersection. save tank state. and now we can unset bullets
//    if (count($bullets) !== 0) {
//        TankRegistry::unsetBullets(); // todo too early unsetting bullets
//        BulletRegistry::unsetRegistry();//todo refactor
//    }
//    BulletRegistry::unsetRegistry();//todo refactor
//    $storage1 = TankRegistry::getStorageJSON();
//    var_dump('$storage1');
//    var_dump($storage1);
    $encMessage = WebSocket::encode($storage);
    fwrite($connect, $encMessage);
    return true;
}
