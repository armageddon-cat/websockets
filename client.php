<?php
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 16.06.2016
 * Time: 15:27
 */
$fp = stream_socket_client("tcp://127.0.0.1:8000", $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    echo "conn". "\n";
    $contents = stream_get_contents($fp);
    echo $contents;
    fclose($fp);
}