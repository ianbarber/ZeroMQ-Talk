<?php 

$ctx = new ZMQContext();
$req = 
  new ZMQSocket($ctx, ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:5454");

while(true) {
    usleep($_SERVER['argv'][2]);
    $req->send($_SERVER['argv'][1]  . ": Hello");
    echo date("H:i:s ") . $req->recv();
    echo PHP_EOL;
}