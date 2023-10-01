<?php
// Publisher
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

$cloudamqp_url = $_ENV['CLOUDAMQP_URL'];
$url = parse_url($cloudamqp_url);
$vhost = substr($url['path'], 1);

$connection = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);
$channel = $connection->channel();

$channel->queue_declare('jobs', false, true, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', '');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
