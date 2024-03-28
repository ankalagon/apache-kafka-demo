<?php

namespace Demo\Factory;

use Demo\Infrastructure\Queue\KafkaEngine;
use Demo\Infrastructure\Queue\RedisEngine;
use Demo\Producer;

class KafkaProducerFactory
{
    public static function create(string $topicName): Producer
    {
        return new Producer(new RedisEngine($topicName));
    }
}
