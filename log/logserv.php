<?php

$ctx = new ZMQContext();
$in = $ctx->getSocket(ZMQ::SOCKET_PULL);
$in->bind("tcp://*:5555");

while(true) {
    $messages = $in->recvMulti();
    // ... write the messages to disk or similar in one batch
    echo "Messages Received: ", count($messages), PHP_EOL;
    foreach($messages as $msg) {
        $a = json_decode($msg);
        echo date("Y-m-d h:i:s", $a->time), " ", $a->msg;
        echo PHP_EOL;
    }
}