<?php

namespace Demo\Infrastructure\Queue;

use Demo\Infrastructure\Factory\KafkaConsumerFactory;
use Demo\Infrastructure\Factory\KafkaProducerFactory;

class KafkaEngine implements Engine
{
    private $producer;

    private $topic;

    private $consumer;

    public function __construct(
        private string $topicName,
        private ?string $consumerGroup = null,
        private ?string $offset = null
    ) {
    }

    public function publish($payload): bool
    {
        if (!$this->producer) {
            $this->initProducer();
        }

        try {
            $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);
        } catch (\Exception $exception) {
            $this->producer->flush(10000);
            $this->publish($payload);
        }
        return true;
    }

    public function consume()
    {
        if (!$this->consumer) {
            $this->initConsumer();
        }

        do {
            $message = $this->consumer->consume(100);

            if ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
                continue;
            }
            if ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                continue;
            }

            //$this->consumer->commit($message);
            return $message;
        } while (true);
    }

    /**
     * @return void
     */
    private function initProducer()
    {
        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('bootstrap.servers', sprintf('%s:9092', getenv('KAFKA_BOOTSTRAP_HOST')));
        $conf->set('queue.buffering.max.kbytes', 10000000);

        $this->producer = new \RdKafka\Producer($conf);

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('message.timeout.ms', (string) 30000);
        $topicConf->set('request.required.acks', (string) -1);
        $topicConf->set('request.timeout.ms', (string) 5000);
        $conf->set('queue.buffering.max.kbytes', 10000000);
        $this->topic = $this->producer->newTopic($this->topicName, $topicConf);
    }

    /**
     * @return void
     */
    private function initConsumer()
    {
        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string)LOG_DEBUG);
        $conf->set('group.id', $this->consumerGroup);
        $conf->set('bootstrap.servers', 'kafka:9092');
        $conf->set('auto.offset.reset', $this->offset);
        $conf->set('enable.auto.commit', 'true');
        $this->consumer = new \RdKafka\KafkaConsumer($conf);
        $this->consumer->subscribe([$this->topicName]);
    }

    function __destruct() {
        if ($this->producer) {
            $err = $this->producer->flush(10000);
            if ($err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
                throw new \RuntimeException('Failed to flush producer. Messages might not have been delivered.');
            }
        }
    }
}
