<?php

$name = htmlspecialchars($_POST['name']);
$message = htmlspecialchars($_POST['message']);

$context = new ZMQContext();
$send = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$send->connect('tcp://localhost:5567');
if($message == 'm:joined') {
	$send->send("<em>" . $name . " has joined</em>");
} else {
	$send->send($name . ': ' . $message);
}
exit();