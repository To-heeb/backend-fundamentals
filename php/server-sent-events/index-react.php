<?php

use React\EventLoop\Loop;
use React\Stream\ThroughStream;

require __DIR__ . '/vendor/autoload.php';

$url = "127.0.0.1:8080";

$stream_url = "http://localhost:8080/stream";
$client = new React\Http\Browser();

$client->get($stream_url)->then(function (Psr\Http\Message\ResponseInterface $response) {
    $stream = new ThroughStream();


    $timer = Loop::addPeriodicTimer(0.5, function () use ($stream) {
        $stream->write(microtime(true) . PHP_EOL);
    });

    // end stream after a few seconds
    $timeout = Loop::addTimer(5.0, function () use ($stream, $timer) {
        Loop::cancelTimer($timer);
        $stream->end();
    });

    // stop timer if stream is closed (such as when connection is closed)
    $stream->on('close', function () use ($timer, $timeout) {
        Loop::cancelTimer($timer);
        Loop::cancelTimer($timeout);
    });

    // $time = date('r');
    // return new React\Http\Message\Response(
    //     React\Http\Message\Response::STATUS_OK,
    //     [
    //         'Content-Type' => 'text/event-stream'
    //     ],
    //     "data: The server time is: {$time}\n\n"
    // );

    // return new React\Http\Message\Response(
    //     React\Http\Message\Response::STATUS_OK,
    //     array(
    //         'Content-Type' => 'text/plain'
    //     ),
    //     $stream
    // );
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    return React\Http\Message\Response::plaintext(
        "Hello World!\n"
    );
});

$socket = new React\Socket\SocketServer($url);
$http->listen($socket);
