<?php
// Consumer

require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;


$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

$cloudamqp_url = $_ENV['CLOUDAMQP_URL'];
$url = parse_url($cloudamqp_url);
// var_dump($url['user']);
// exit;
$vhost = substr($url['path'], 1);

$connection = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);
$channel = $connection->channel();

$channel->queue_declare('jobs', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('jobs', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}
