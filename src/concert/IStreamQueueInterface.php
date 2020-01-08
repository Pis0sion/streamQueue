<?php


namespace Pis0sion\redis\concert;

/**
 * Interface IStreamQueueInterface
 * @package Pis0sion\redis\concert
 */
interface IStreamQueueInterface
{
    /**
     * @param string $queueName
     * @param array $message
     * @return string
     */
    public function push(string $queueName, array $message): string;

    /**
     * @param string $name
     * @param string $streamName
     * @param mixed ...$parameter
     * @return mixed
     */
    public function getStream(string $name, string $streamName, ...$parameter);

    /**
     * @param string $queueName
     * @return int
     */
    public function len(string $queueName): int;

    /**
     * @param string $queueName
     * @param string $queue_start
     * @param string $queue_end
     * @param int|null $count
     * @return array|null
     */
    public function range(string $queueName, string $queue_start = "-", string $queue_end = "+", int $count = null): ?array;

    /**
     * @param string $queueName
     * @param string $queue_start
     * @param string $queue_end
     * @param int|null $count
     * @return array|null
     */
    public function revRange(string $queueName, string $queue_end = "-", string $queue_start = "+", int $count = null): ?array;

    /**
     * @param array $streams
     * @param array $parameter
     * @return array|null
     */
    public function read(array $streams, ...$parameter): ?array;

    /**
     * @param string $type
     * @param string $stream
     * @param string $groupName
     * @param mixed ...$parameter
     * @return mixed
     */
    public function group(string $type, string $stream, string $groupName, ...$parameter);


    /**
     * @param string $groupName
     * @param string $consumer
     * @param array $streams
     * @param mixed ...$parameter
     * @return array|null
     */
    public function readGroup(string $groupName, string $consumer, array $streams, ...$parameter);


    /**
     * @param string $stream
     * @param string $groupName
     * @param mixed ...$parameter
     * @return array|null
     */
    public function readToPending(string $stream, string $groupName, ...$parameter): ?array;

    /**
     * @param string $stream
     * @param string $groupName
     * @param array $message
     * @return int|null
     */
    public function ack(string $stream, string $groupName, array $message): ?int;

    /**
     * @param string $stream
     * @param array $message
     * @return int|null
     */
    public function delete(string $stream, array $message): ?int;

    /**
     * @param string $stream
     * @param int $max_len
     * @param mixed ...$parameter
     * @return int|null
     */
    public function stapleStream(string $stream, int $max_len, ...$parameter): ?int;

}