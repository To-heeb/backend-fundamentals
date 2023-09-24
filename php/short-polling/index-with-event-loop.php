<?php

require 'vendor/autoload.php';

use React\EventLoop\Loop;

function submit()
{
    $job = getdata();
    $jobId = "job:" . time();
    $job[$jobId] = 0;
    echo "\n" . $jobId . " \n\n";

    updateJob($jobId, 0, 0);
    // Loop::addTimer(2, function () use ($jobId) {
    //     updateJob($jobId, 0, 0);
    // });
}

function checkStatus($jobId)
{
    $job = getdata();
    if ($job[$jobId]) {
        echo "\n " . sprintf("Jobstatus: %u%%", $job[$jobId]) . " \n\n";
    } else {
        echo "Unidentified job";
    }
}

function updateJob($jobId, $progress, $time = 0)
{
    $job = getdata();
    $job[$jobId] = $progress;
    updatedata($job);
    if ($progress == 100) return;

    Loop::addTimer(1, function () use ($jobId, $progress) {
        $job = getdata();
        $job[$jobId] = $progress + 10;
        updatedata($job);
        if ($progress == 100) {
            return;
        }
        updateJob($jobId, $progress + 10);
    });
}

function getdata()
{
    if (file_exists('cache.json')) {
        $jsonData = file_get_contents('cache.json');
        $data = json_decode($jsonData, true);
        return $data;
    }
    return [];
}

function updatedata($job)
{
    if (file_exists('cache.json')) {
        $jsonData = json_encode($job);
        $result = file_put_contents('cache.json', $jsonData);
    }
}

function routes($route_path, $method)
{
    $segment = explode('/', $route_path);
    $path = $segment[1];

    if ($path ==  "checkstatus" && $method == "GET") {
        if (isset($_GET['jobId'])) {
            $jobId = $_GET['jobId'];
            checkStatus($jobId);
        } else {
            echo "No job_id specified";
        }
    } elseif ($path ==  "submit" && $method == "POST") {
        submit();
    } else {
        echo "Please 😎 kindly get out of here";
    }
}

Loop::addTimer(0, function () {
    routes($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
});
