<?php

$context = new ZMQContext();
$frontend = new ZMQSocket($context, ZMQ::SOCKET_XREP);
$backend = new ZMQSocket($context, ZMQ::SOCKET_XREQ);
$frontend->bind('tcp://*:5454');
$backend->bind('tcp://*:5455');

$poll = new ZMQPoll();
$poll->add($frontend, ZMQ::POLL_IN);
$poll->add($backend, ZMQ::POLL_IN);
$readable = $writeable = array();

while(true) {
    $events = $poll->poll($readable, $writeable);
    foreach($readable as $socket) {
        if($socket === $frontend) {
            $messages = $frontend->recvMulti();
            $backend->sendMulti($messages);
        } else if($socket === $backend) {
            $messages = $backend->recvMulti();
            $frontend->sendMulti($messages);
        }
    }
}