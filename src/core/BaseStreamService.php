<?php


namespace Pis0sion\redis\core;

use Closure;
use Pis0sion\redis\entity\Streams;
use Throwable;

/**
 * Class BaseStreamService
 * @package Pis0sion\redis\core
 */
abstract class BaseStreamService
{
    /**
     * @var string
     */
    protected $streamName;

    /**
     * @var string
     */
    protected $groupName;

    /**
     * @var string
     */
    protected $consumerName;

    /**
     * @var
     */
    protected $stream;

    /**
     * BaseStreamService constructor.
     */
    public function __construct()
    {
        $this->stream = new Streams();
    }

    /**
     * @param string $streamName
     */
    protected function setStreamName(string $streamName): void
    {
        $this->streamName = $streamName;
    }

    /**
     * @param string $groupName
     */
    protected function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }

    /**
     * @param string $consumerName
     */
    protected function setConsumerName(string $consumerName): void
    {
        $this->consumerName = $consumerName;
    }

    /**
     * @param string $streamName
     * @param string $groupName
     * @param string $consumerName
     */
    protected function initStream(string $streamName, string $groupName, string $consumerName)
    {
        $this->setStreamName($streamName);
        $this->setGroupName($groupName);
        $this->setConsumerName($consumerName);
    }

    /**
     * @param $streamName
     * @param $groupName
     * @param $consumerName
     */
    public function process($streamName, $groupName, $consumerName)
    {
        $this->initStream($streamName, $groupName, $consumerName);
        $this->stream->group('CREATE', $this->streamName, $this->groupName, 0, true);
        $handle = $this->handle();
        $this->consumer($handle);
    }

    /**
     * @param Closure $handle
     */
    protected function consumer(Closure $handle)
    {
        Begin:
            try {
                //  读取
                $result = $this->stream->readGroup($this->groupName, $this->consumerName, [$this->streamName => '>'], 1, 2 * 1000);
                if (null == $result) {
                    // 休眠1s
                    sleep(1);
                } else {
                    $message = $result[$this->streamName];
                    //  处理逻辑
                    $handle($message);
                }
                /**
                 * 跳转到开始
                 */
                goto Begin ;

            } catch (Throwable $throwable) {

                echo $throwable->getMessage();

                goto End ;
            }

        End :
        exit("异常退出");
    }

    /**
     * @return Closure
     */
    protected function handle(): Closure
    {
        return Closure::fromCallable([$this, 'doProcess']);
    }

    /**
     * @param string $key
     * @return int|null
     */
    protected function ack(string $key): ?int
    {
        return $this->stream->ack($this->streamName, $this->groupName, [$key]);
    }

    /**
     * @param array $message
     */
    abstract protected function doProcess(array $message);

}