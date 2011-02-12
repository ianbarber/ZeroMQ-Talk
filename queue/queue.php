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
            do {
                $message = $frontend->recv();
                $more = $frontend->getSockOpt(ZMQ::SOCKOPT_RCVMORE);
                $backend->send($message, $more ? ZMQ::MODE_SNDMORE : null);
            } while($more);
        } else if($socket === $backend) {
            do {
                $message = $backend->recv();
                $more = $backend->getSockOpt(ZMQ::SOCKOPT_RCVMORE);
                $frontend->send($message, $more ? ZMQ::MODE_SNDMORE : null);
            } while($more);
        }
    }
}