<?php
define("NUM_WORKERS", 10);

// Start workers
for($i = 0; $i < NUM_WORKERS; $i++) {
    echo "Starting Worker $i\n";
    if(pcntl_fork() == 0) {
        `php work.php`;
        exit;
    }
}

$context = new ZMQContext();
$work = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$ctrl = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$work->setSockOpt(ZMQ::SOCKOPT_HWM, NUM_WORKERS);
$ctrl->setSockOpt(ZMQ::SOCKOPT_HWM, 1);
$work->bind("ipc:///tmp/work");
$ctrl->bind("ipc:///tmp/control");

sleep(1);

$fh = fopen('data.txt', 'r');

while($data = fgets($fh)) {
    $work->send($data);
}

for($i = 0; $i < NUM_WORKERS+1; $i++) {
    $ctrl->send("END");
}
