<?php
/* Accept event messages and republish them across PGM */
$context = new ZMQContext();

$out = new ZMQSocket($context, ZMQ::SOCKET_PUB);
$out->setSockOpt(ZMQ::SOCKOPT_RATE, 10000);
$out->connect("epgm://eth0;239.192.0.1:7601");

$in = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$in->bind("tcp://*:6767");

$device = new ZMQDevice(ZMQ::DEVICE_FORWARDER, $in, $out);
