<?php

$bufferSize = $_SERVER['argc'] > 1 ? $_SERVER['argv'][1] : 3;
$ctx = new ZMQContext();
$in = $ctx->getSocket(ZMQ::SOCKET_PULL);
$out = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$in->bind("ipc:///tmp/logger");
$out->connect("tcp://localhost:5555");

$messages = array();
while(true) {
   $message = $in->recv();
   echo "Received Log", PHP_EOL;
   $messages[] = $message;
   if(count($messages) == $bufferSize) {
       echo "Forwarding Buffer", PHP_EOL;
       foreach($messages as $id => $msg) {
           $out->send($msg, $id == $bufferSize-1 ? null : ZMQ::MODE_SNDMORE);
       }
       $messages = array();
   }
}