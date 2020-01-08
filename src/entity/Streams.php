<?php /** @noinspection ALL */


namespace Pis0sion\redis\entity;


use Pis0sion\redis\concert\IStreamQueueInterface;
use Pis0sion\redis\core\StreamQueue;

/**
 * Class Streams
 * @package Pis0sion\redis\entity
 */
class Streams extends StreamConcert implements IStreamQueueInterface
{

    /**
     * 实例化channel
     * @inheritDoc
     */
    public function initChannel()
    {
        // TODO: Implement initChannel() method.
        return StreamQueue::getInstance();
    }

    /**
     * 创建stream队列，并且添加消息
     * @inheritDoc
     */
    public function push(string $queueName, array $message): string
    {
        // TODO: Implement push() method.
        return $this->channel->xAdd($queueName, "*", $message);
    }

    /**
     * 获取stream流队列的监控信息
     * @inheritDoc
     */
    public function getStream(string $name, string $streamName, ...$parameter)
    {
        // TODO: Implement stapleStream() method.
        if (count($parameter) == 0) {
            return $this->channel->xInfo($name, $streamName);
        }
        return $this->channel->xInfo($name, $streamName, ...$parameter);
    }

    /**
     * 获取流队列的长度
     * @inheritDoc
     */
    public function len(string $queueName): int
    {
        return $this->channel->xLen($queueName);
    }

    /**
     * 获取流队列中的元素
     * @inheritDoc
     */
    public function range(string $queueName, string $queue_start = "-", string $queue_end = "+", int $count = null): ?array
    {
        $list = [];
        if (empty($count)) {
            $list = $this->channel->xRange($queueName, $queue_start, $queue_end);
        } else {
            $list = $this->channel->xRange($queueName, $queue_start, $queue_end, $count);
        }
        return $list;
    }

    /**
     * 倒叙获取流队列中的元素
     * @inheritDoc
     */
    public function revRange(string $queueName, string $queue_end = "+", string $queue_start = "-", int $count = null): ?array
    {
        $list = [];
        if (empty($count)) {
            $list = $this->channel->xRevRange($queueName, $queue_end, $queue_start);
        } else {
            $list = $this->channel->xRevRange($queueName, $queue_end, $queue_start, $count);
        }
        return $list;
    }

    /**
     * 监听流队列中的消息
     * @inheritDoc
     */
    public function read(array $streams, ...$parameter): ?array
    {
        // TODO: Implement read() method.

        if (count($parameter) == 0) {
            return $this->channel->xRead($streams);
        }
        return $this->channel->xRead($streams, ...$parameter);
    }

    /**
     * 管理组的信息
     * @inheritDoc
     */
    public function group(string $type, string $stream, string $groupName, ...$parameter)
    {
        // TODO: Implement group() method.
        if (count($parameter) == 0) {
            return $this->channel->xGroup($type, $stream, $groupName);
        }
        return $this->channel->xGroup($type, $stream, $groupName, ...$parameter);
    }

    /**
     * 已组的方式进行读取消息
     * @inheritDoc
     */
    public function readGroup(string $groupName, string $consumer, array $streams, ...$parameter)
    {
        // TODO: Implement readGroup() method.
        if (count($parameter) == 0) {
            return $this->channel->xReadGroup($groupName, $consumer, $streams);
        }

        return $this->channel->xReadGroup($groupName, $consumer, $streams, ...$parameter);
    }

    /**
     * 读取pending中的元素
     * @inheritDoc
     */
    public function readToPending(string $stream, string $groupName, ...$parameter): ?array
    {
        // TODO: Implement readToPending() method.
        if (count($parameter) == 0) {
            return $this->channel->xPending($stream, $groupName);
        }
        return $this->channel->xPending($stream, $groupName, ...$parameter);
    }

    /**
     * 响应流队列，从pending删除消息
     * @inheritDoc
     */
    public function ack(string $stream, string $groupName, array $message): ?int
    {
        // TODO: Implement ack() method.
        return $this->channel->xAck($stream, $groupName, $message);
    }

    /**
     * 删除流队列中的消息
     * @inheritDoc
     */
    public function delete(string $stream, array $message): ?int
    {
        // TODO: Implement delete() method.
        return $this->channel->xDel($stream, $message);
    }

    /**
     * 创建定长的流队列
     * @inheritDoc
     */
    public function stapleStream(string $stream, int $max_len, ...$parameter): ?int
    {
        // TODO: Implement stapleStream() method.
        if (count($parameter) == 0) {
            return $this->channel->xTrim($stream, $max_len);
        }
        return $this->channel->xTrim($stream, $max_len, ...$parameter);
    }

}