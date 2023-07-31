<?php


echo "1\n";

if (file_exists("text.txt")) {
    // Load data from file
    $data = file_get_contents("text.txt");

    echo $data . PHP_EOL;
}

echo "2\n";
