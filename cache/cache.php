<?php
// cache client should mark extended date in memcached or similar
$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_PUB);
$socket->connect("ipc:///tmp/usercache");
$socket->connect("ipc:///tmp/datacache");
$type = array('users', 'data');

while(true) {
    $socket->send($type[array_rand($type)], ZMQ::MODE_SNDMORE);
    $socket->send(rand(0, 12));
    sleep(rand(0,3));
}
