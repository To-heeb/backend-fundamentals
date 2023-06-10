<?php

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

require __DIR__ . '/vendor/autoload.php';

class Greeter
{
    private $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function greet(string $name)
    {
        $this->loop->addTimer(5.0, function () use ($name) {
            echo 'Hello ' . $name . '!' . PHP_EOL;
        });

        echo "Should I be printed first" . PHP_EOL;
    }
}

$greeter = new Greeter(Loop::get());
$greeter->greet('Alice');
#$greeter->greet('Bob');
