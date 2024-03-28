<?php

namespace Demo;

use Demo\Factory\ConsumerFactory;

require_once 'vendor/autoload.php';

$vendor = $argv[1] ?? null;
$topic = $argv[2] ?? null;
if (!$topic || !$vendor) {
    echo 'usage php %s vendor topic_name consumer_group_id offset'.PHP_EOL;
    die();
}

$consumerGroup = $argv[3] ?? null;
$offset = $argv[4] ?? 'earliest';

$consumer = ConsumerFactory::create($vendor, $topic, $consumerGroup, $offset);
$message = $consumer->consume();
print_r($message);
