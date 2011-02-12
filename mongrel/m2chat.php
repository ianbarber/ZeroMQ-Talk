<?php
namespace m2php;
require 'm2conn.php';
require 'm2req.php';
require 'm2tools.php';
$sender_id = "82209006-86FF-4982-B5EA-D1E29E55D481";

$conn = new  \m2php\Connection($sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");
$ctx = new \ZMQContext();
$sub = new \ZMQSocket($ctx, \ZMQ::SOCKET_SUB);
$sub->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, '');
$sub->connect('tcp://localhost:5566');
$poll = new \ZMQPoll();
$poll->add($sub, \ZMQ::POLL_IN);
$poll->add($conn->reqs, \ZMQ::POLL_IN);
$read = $write = $ids = array();
$sender = '';
$http_head =  "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nTransfer-Encoding: chunked\r\n\r\n";

while (true) {
    $ev = $poll->poll($read, $write);
    foreach($read as $r) {
        if($r === $sub) {
            $msg = "<script type='text/javascript'>parent.updateChat('" . str_replace("'", "\'", $sub->recv()) . "');</script>\r\n";
            $conn->send($sender, implode(' ', $ids), sprintf("%x\r\n%s", strlen($msg) , $msg));
        } else {
            $req = $conn->recv();
            $sender = $req->sender;
            if($req->is_disconnect()) {
                unset($ids[$req->conn_id]);
            } else {
                $ids[$req->conn_id] = $req->conn_id;
                $conn->send($sender, $req->conn_id, $http_head);
            }
        }
    }
}