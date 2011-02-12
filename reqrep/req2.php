<?php 

$ctx = new ZMQContext();
$req = 
  new ZMQSocket($ctx, ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:5454");

while(true) {
    sleep($_SERVER['argv'][2]);
    $req->send($_SERVER['argv'][1]  . ": Hello");
    echo $req->recv();
    echo PHP_EOL;
}