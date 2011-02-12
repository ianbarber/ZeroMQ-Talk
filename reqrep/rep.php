<?php

$context = new ZMQContext();
$server =$context->getSocket(ZMQ::SOCKET_REP);
$server->bind("tcp://*:5454");
while(true) {
    $message = $server->recv();
    echo "Sending $message World", PHP_EOL;
    $server->send($message . " World");
}