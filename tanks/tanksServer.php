<?php
declare(strict_types=1);
namespace Tanks;

use WebSocket\WebSocket;

require __DIR__ . '/../vendor/autoload.php';

//$socket   = stream_socket_server('tcp://127.0.0.1:8000', $errNumber, $errString);
$socket = stream_socket_server('tcp://185.154.13.92:8124', $errNumber, $errString);
if (!$socket) {
    die("$errString ($errNumber)\n");
}
$connects = [];
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
                ServerActions::onOpen($connect);//call user scenario
                //  $data = fread($connect, 100000); instead of info to function
            }
        }
        unset($read[array_search($socket, $read)]);//then remove from list available for read
    }
    // if any new messages
    if (!empty($read)) {
        $serverTime = DateTimeUser::createDateTimeMicro();
        foreach ($read as $currentConnect) {//working out all connections
            $data = fread($currentConnect, 100000);
            if (!strlen($data)) { //connection was closed
                fclose($currentConnect);
                unset($connects[array_search($currentConnect, $connects)]);
                ServerActions::onClose(/*$currentConnect*/);//call user scenario
                continue;
            }
    
            ServerActions::onMessage($currentConnect, $data, $serverTime);//call user scenario
        }
    } else {
    // no messages. just send current state
        // make shooting for each present bullet, checking hit
        BulletRegistry::fireEach();
        // move one step forward each existing bullet
        BulletRegistry::moveBullets();
        foreach ($connects as $curConnect) {//working out all connections
            $storage = TankRegistry::getStorageJSON();
            $encMessage = WebSocket::encode($storage);
            fwrite($curConnect, $encMessage);
        }
    }
    usleep(200000);
}

fclose($socket);
