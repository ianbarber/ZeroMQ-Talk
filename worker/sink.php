<?php

$ctx = new ZMQContext();
$results = $ctx->getSocket(ZMQ::SOCKET_PULL);
$results->bind("ipc:///tmp/results");
$ctrl = $ctx->getSocket(ZMQ::SOCKET_PULL);
$ctrl->setSockOpt(ZMQ::SOCKOPT_HWM, 1);
$ctrl->connect("ipc:///tmp/control");

$poll = new ZMQPoll();
$poll->add($results, ZMQ::POLL_IN);

$read = $write = array();
$total = 0;
while(true) {
    $ev = $poll->poll($read, $write, 100000);
    if($ev) {
        $total += $results->recv();
    } else {
        if($ctrl->recv(ZMQ::MODE_NOBLOCK)) {
            echo $total, PHP_EOL;
            exit();
        }
    }
}

echo "Total: ", $total, PHP_EOL;