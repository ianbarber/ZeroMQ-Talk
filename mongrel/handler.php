<?php

$ctx = new ZMQContext();
$in = $ctx->getSocket(ZMQ::SOCKET_PULL);
$in->connect('tcp://localhost:9997');

$out = $ctx->getSocket(ZMQ::SOCKET_PUB);
$out->connect('tcp://localhost:9996');

$http = "HTTP/1.1 200 OK\r\nContent-Length: %s\r\n\r\n%s";

while(true) {
	$msg = $in->recv();
	list($uuid, $id, $path, $rest) = explode(" ", $msg, 4);
	$res = $uuid . " " . strlen($id) . ':' . $id . ", ";
	$res .= sprintf($http, 6, "Hello!");
	$out->send($res);
}
