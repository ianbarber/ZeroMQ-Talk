<?php

$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_SUB);
if($_SERVER['argc'] > 1) {
    $socket->setSockOpt(ZMQ::SOCKOPT_IDENTITY, $_SERVER['argv'][1]);
}
// Follow a random 1000 people
for($i = 0; $i<1000; $i++) {
   $socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, rand(100000, 999999));
}
$socket->connect("ipc:///tmp/events");
$i = 0; 
// collect 1000 messages
while($i++ < 1000) {
    $user = $socket->recv();
    $update = $socket->recv();
    echo sprintf("%s %s %s", $user, $update, PHP_EOL);
}