<?php

namespace Demo\Infrastructure\Queue;

class RedisEngine implements Engine
{
    private $redis;

    public function __construct(
        private string $topicName,
    ) {
        $this->redis = new \Redis();

        try {
            $ifConnected = $this->redis->connect('redis', '6379', 0, null, 100);
            if (!$ifConnected) {
                throw new \RuntimeException('Unable to connect to redis');
            }
        } catch (\RuntimeException $exception) {
            throw $exception;
        }
    }

    public function publish($payload): bool
    {
        $this->redis->rPush($this->topicName, $payload);
        return true;
    }

    public function consume()
    {
        $message = $this->redis->blPop($this->topicName, 1000);
        if (!$message) {
            $message = $this->consume();
        }

        return $message;
    }
}