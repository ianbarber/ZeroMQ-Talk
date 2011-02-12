<?php

/* The distro listens on PGM, and republishes the messages to it's local clients */

$context = new ZMQContext();
$in = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$in->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, '');
$in->setSockOpt(ZMQ::SOCKOPT_RATE, 10000);
$in->connect("epgm://;239.192.0.1:7601");
$out = new ZMQSocket($context, ZMQ::SOCKET_PUB);
$out->bind("ipc:///tmp/events");

$device = new ZMQDevice(ZMQ::DEVICE_FORWARDER, $in, $out);
