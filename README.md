# KAFKA DEMO

# how to start?

```bash
docker-composer up
```

builds and run 4 container, ready to go. 

`kafka` - container with kafka broken

`kafka-ui` - container with Kafka UI - http://localhost:8888

`kafka-app` - container with code examples

`kafka-demo-redis` - container with redis


# working with kafka


### create topic
You dont need to create kafka topic, because as part of a demo topic will be created automatically.
Buy if you want to create kafka topic by hand you can use:
```bash
docker exec -it kafka /opt/kafka/bin/kafka-topics.sh --create --partitions 10 --topic topic-1 --bootstrap-server localhost:9092
```

### publishing events

```bash
docker exec -it kafka-app php /app/producer.php kafka topic-1 100
```

### consuming events 

```bash
docker exec -it kafka-app  php /app/consumeOneMessage.php kafka topic-1
```

```bash
docker exec -it kafka-app  php /app/consumer.php kafka topic-1 1000 consumer1
```

### working with redis
All above commands also works for redis for comparison purpouses. All You need to do is to change first parameter from `kafka` to `redis`

```bash
docker exec -it kafka-app  php /app/producer.php redis topic-1 100
```




