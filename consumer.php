<?php

namespace Demo;

use Demo\Factory\ConsumerFactory;
use Demo\Kafka\Consumer;

require_once 'vendor/autoload.php';

$vendor = $argv[1] ?? null;
$topic = $argv[2] ?? null;
if (!$topic || !$vendor) {
    echo 'usage php %s vendor topic_name number_of_messages cnsumer_group_id'.PHP_EOL;
    die();
}
$numberOfMessages = $argv[3] ?? null;
$consumerGroup = $argv[4] ?? null;
$offset = $argv[5] ?? 'earliest';

$consumer = ConsumerFactory::create($vendor, $topic, $consumerGroup, $offset);

$messagesCount = 0;
$startTime = microtime(true);
while ($message = $consumer->consume()) {
    $messagesCount++;
    if ($messagesCount >= $numberOfMessages) {
        break;
    }
}

$stopTime = microtime(true);
$elapsed = $stopTime-$startTime;
printf('consumed %s messages in %s'.PHP_EOL, $messagesCount, $elapsed);

