<?php

$context = new ZMQContext();
$server =$context->getSocket(ZMQ::SOCKET_REP);
$server->connect("tcp://localhost:5455");
while(true) {
    $message = $server->recv();
    echo "Sending $message World", PHP_EOL;
    $server->send($message . " World");
}