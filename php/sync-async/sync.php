<?php

use React\EventLoop\Loop;

require __DIR__ . '/vendor/autoload.php';

echo "1\n";

Loop::addTimer(0.1, function () {
    // Check if file exists
    if (file_exists("text.txt")) {
        // Load data from file
        $data = file_get_contents("text.txt");

        echo $data;
    }
});

echo "2\n";
