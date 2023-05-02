<?php

namespace App\Helper;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Queue
{
    public static function createConnectionAndChannel(string $queueName): array
    {
        $connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASSWORD')
        );

        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);

        return [$connection, $channel];
    }
}