<?php 

$ctx = new ZMQContext();
$req = 
  new ZMQSocket($ctx, ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:5454");

$req->send("Hello");
echo $req->recv();
echo PHP_EOL;