<?php

$ctx = new ZMQContext();
$pub = $ctx->getSocket(ZMQ::SOCKET_PUB);
$pub->bind('tcp://*:5566');
$pull = $ctx->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://*:5567');

while(true) {
	$message = $pull->recv();
	echo "Got ", $message, PHP_EOL;
	$pub->send($message);
}