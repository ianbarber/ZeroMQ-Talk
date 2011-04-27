<?php 

$ctx = new ZMQContext();
$work = $ctx->getSocket(ZMQ::SOCKET_PULL);
$work->setSockOpt(ZMQ::SOCKOPT_HWM, 1);
$work->connect("ipc:///tmp/work");
$sink = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$sink->connect("ipc:///tmp/results");
$ctrl =$ctx->getSocket(ZMQ::SOCKET_PULL);
$ctrl->setSockOpt(ZMQ::SOCKOPT_HWM, 1);
$ctrl->connect("ipc:///tmp/control");

$poll = new ZMQPoll();
$poll->add($work, ZMQ::POLL_IN);
$read = $write = array();

while(true) {
    $events = $poll->poll($read, $write, 5000);
    if($events) {
        $message = $work->recv();
        $sink->send(strlen($message));
    } else {
        if($ctrl->recv(ZMQ::MODE_NOBLOCK)) {
            echo "Got END";
            exit();
        }
    }    
}