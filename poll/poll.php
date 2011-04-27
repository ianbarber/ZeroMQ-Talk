<?php

$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$socket->bind("tcp://*:5555");

$poll = new ZMQPoll();
$poll->add($socket, ZMQ::POLL_IN);
$poll->add(STDIN, ZMQ::POLL_IN);
$readable = $writeable = array();

while(true) {
	$events = $poll->poll($readable, $writeable);
	if($readable[0] === $socket) {
		echo "ZMQ: " . $readable[0]->recv();
	} else {
		echo "STDIN: " . fgets($readable[0]);
	}
}
