<?php

$context = new ZMQContext();
$sub = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$sub->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, '');
$sub->connect('tcp://localhost:5566');
$poll = new ZMQPoll();
$poll->add($sub, ZMQ::POLL_IN);
$readable = $writeable = array();
// Hack for chrome etc. to start polling
echo str_repeat("<span></span>", 100);
ob_flush();
flush();
while(true) {
    $events = $poll->poll($readable, $writeable, 5000000);
    if($events > 0) {
        echo "\n\n<script type='text/javascript'>parent.updateChat('" . str_replace("'", "\'", $sub->recv()) ."');</script>\n\n";
    } else {
        echo ".";
    }
    ob_flush();
    flush();
}