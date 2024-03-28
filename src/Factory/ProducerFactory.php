<?php

namespace Demo\Factory;

use Demo\Infrastructure\Queue\KafkaEngine;
use Demo\Infrastructure\Queue\RedisEngine;
use Demo\Producer;

class ProducerFactory
{
    public static function create(string $vendor, string $topicName): Producer
    {
        if ($vendor == 'kafka') {
            return new Producer(new KafkaEngine($topicName));
        } elseif ($vendor == 'redis') {
            return new Producer(new RedisEngine($topicName));
        }

        throw new \Exception('Not recognize vendor');
    }
}
