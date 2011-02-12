<?php 

$context = new ZMQContext(); 
$socket = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$socket->connect("tcp://localhost:5555");

$fh = fopen("php://stdin", "r");
while($data = fgets($fh)) {
	$socket->send($data);
}
