<?php


function  stream()
{

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");

    $time = date('r');
    echo "data: The server time is: {$time}\n\n";
}

function routes($route_path, $method)
{
    $segment = explode('/', $route_path);
    $path =  $segment[1];
    if ($path == "" && $method == "GET") {

        echo "Hello, World";
    }

    if ($path ==  "stream" && $method == "GET") {
        stream();
    }

    if (!in_array($path, ["", "stream"])) {
        echo "Please 😎 kindly getout of here";
    }
}


routes($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
