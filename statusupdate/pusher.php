<?php

$context = new ZMQContext();
$socket = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$socket->setSockOpt(ZMQ::SOCKOPT_HWM, 5);
$socket->connect("tcp://localhost:6767");

$messages = array(
    "I still feel stress, still gotta get something off my chest",
    "trunk full of funk, I ain't never been a punk",    
    "looking gravy, looking real throwed",    
    "Flex my bicep, then I swoll on",    
    "I'm like the ghetto popeye, but I don't need spinach",    
    'Trying to send me to the penitentiary',
);

$total = 0;
$start = microtime(true);

/* Generate random updates for one of 900,000 users */
while($total++ < 1000000) {
    $userid = rand(100000, 999999);
    $socket->send($userid, ZMQ::MODE_SNDMORE);
    $msg = $messages[array_rand($messages)];
    $socket->send($msg);
    echo $userid . "-" . $msg . PHP_EOL;
    usleep(100);
}

sprintf("%d messages send in %f seconds", $total, (microtime(true)-$start)/1000);