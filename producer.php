<?php

namespace Demo;

use Demo\Kafka\Producer;
use Demo\Factory\ProducerFactory;

require_once 'vendor/autoload.php';

$vendor = $argv[1] ?? null;
$topic = $argv[2] ?? null;
if (!$topic || !$vendor) {
    echo sprintf('usage php %s vendor topic_name number_of_messages'.PHP_EOL, __FILE__);
    die();
}

$numberOfMessages = (int) ($argv[3] ?? 1);
$producer = ProducerFactory::create($vendor, $topic);

$startTime = microtime(true);
for ($i = 0; $i < $numberOfMessages; $i++) {
    $payload = sprintf('payload-%d-%s', $i, microtime());
    $producer->publish($payload);
}
$stopTime = microtime(true);

$elapsed = $stopTime-$startTime;
printf('published %s messages in %s seconds'.PHP_EOL, $numberOfMessages, $elapsed);
