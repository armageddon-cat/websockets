<?php

/**
 * Created by PhpStorm.
 * User: sera
 * Date: 16.06.2016
 * Time: 16:27
 */
class WebSocket
{
    protected $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function handshake()
    {
        $info = array();

        $line           = fgets($this->connect);
        $header         = explode(' ', $line);
        $info['method'] = $header[0];
        $info['uri']    = $header[1];

        //считываем заголовки из соединения
        while ($line = rtrim(fgets($this->connect))) {
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $info[$matches[1]] = $matches[2];
            } else {
                break;
            }
        }

        $address      = explode(':', stream_socket_get_name($this->connect, true)); //получаем адрес клиента
        $info['ip']   = $address[0];
        $info['port'] = $address[1];

        if (empty($info['Sec-WebSocket-Key'])) {
            return false;
        }

        //отправляем заголовок согласно протоколу вебсокета
        $SecWebSocketAccept = base64_encode(pack('H*',
            sha1($info['Sec-WebSocket-Key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $upgrade            = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
                              "Upgrade: websocket\r\n" .
                              "Connection: Upgrade\r\n" .
                              "Sec-WebSocket-Accept:$SecWebSocketAccept\r\n\r\n";
        fwrite($this->connect, $upgrade);

        return $info;
    }
}