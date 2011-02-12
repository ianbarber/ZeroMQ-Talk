<?php

$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "data");
$socket->bind("ipc:///tmp/datacache");

while(true) {
    $cache = $socket->recv();
    $request = $socket->recv();    
    echo "Clearing $cache $request\n";
}
