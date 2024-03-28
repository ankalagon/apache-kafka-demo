<?php

namespace Demo\Factory;

use Demo\Consumer;
use Demo\Infrastructure\Queue\KafkaEngine;
use Demo\Infrastructure\Queue\RedisEngine;

class ConsumerFactory
{
    public static function create(string $vendor, string $topicName, ?string $consumerGroup, string $offset = 'earliest'): Consumer
    {
        if ($vendor == 'kafka') {
            if (!$consumerGroup) {
                $consumerGroup = $topicName.'_test_consumer';
            }
            return new Consumer(new KafkaEngine($topicName, $consumerGroup, $offset));
        } elseif ($vendor == 'redis') {
            return new Consumer(new RedisEngine($topicName));
        }

        throw new \Exception('Not recognize vendor');
    }
}
