<?php

$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "users");
$socket->bind("ipc:///tmp/usercache");

while(true) {
    $cache = $socket->recv();
    $request = $socket->recv();    
    echo "Clearing $cache $request\n";
}