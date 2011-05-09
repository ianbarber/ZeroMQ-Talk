<?php

$ctx = new ZMQContext();
$out = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$out->connect("ipc:///tmp/logger");
$msg = array("time" => time());
$msg['msg'] = $_SERVER['argv'][1];
$out->send(json_encode($msg));