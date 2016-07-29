<?php
if(empty($argv[1])) {
	die('Specify the name of a job to add. e.g, php queue.php PHP_Job');
}

require __DIR__ . '/init.php';
date_default_timezone_set('GMT');
// Resque::setBackend('127.0.0.1:6379', 8);
// Resque::redis()->auth('ufsredis');

// You can also use a DSN-style format:
Resque::setBackend('redis://user:ufsredis@127.0.0.1:6379/8');
//Resque::setBackend('redis://user:pass@a.host.name:3432/2');
//
$len = Resque::redis()->llen('queue:default');

$file = __DIR__.'/../child.php';

$args = array(
	'time' => time(),
    'command'=>'php '.$file
);

// default, EXEC_JOB, ...
$jobId = Resque::enqueue($argv[1], $argv[2], $args, true);
echo "Queued job ".$jobId."\n\n";
