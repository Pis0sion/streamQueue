<?php


namespace Pis0sion\redis\core;

/**
 * Class StreamQueue
 * @package Pis0sion\src\core
 */
class StreamQueue
{
    /**
     * host
     */
    const HOST = "47.95.15.38";

    /**
     * port
     */
    const PORT = 6379;

    /**
     * timeout 超时
     */
    const TIMEOUT = 0.5;

    /**
     * password
     */
    const PASSWORD = "123456";

    /**
     * 实例
     * @var
     */
    protected static $instance;

    /**
     * 设置
     * @var
     */
    protected $options = [
        \Redis::OPT_READ_TIMEOUT => -1,
    ];

    /**
     * 队列
     * @var
     */
    protected $connetion;

    /**
     * StreamQueue constructor.
     */
    protected function __construct()
    {
        $this->reConnect();
    }

    /**
     * 防止序列化
     */
    protected function __wakeup()
    {
    }

    /**
     * 防止克隆
     */
    protected function __clone()
    {
    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static());
    }

    /**
     * @method reConnect
     * @return bool
     */
    public function reConnect(): bool
    {
        $redis = new \Redis();
        $redis->connect(self::HOST, self::PORT, self::TIMEOUT);
        $options = $this->options ?? [];
        foreach ($options as $name => $value) {
            $redis->setOption($name, $value);
        }
        if (self::PASSWORD != "") {
            $redis->auth(self::PASSWORD);
        }
        $this->connetion = $redis;
        return true;
    }

    /**
     * @param string $name
     * @param $arguments
     * @param \Throwable $exception
     * @return mixed
     * @throws \Throwable
     */
    protected function retry(string $name, $arguments, \Throwable $exception)
    {
        // 记录第一次的请求异常信息  $exception->getMessage() ;

        echo $exception->getMessage();

        try {
            $this->reConnect();
            $result = $this->connetion->{$name}(...$arguments);
        } catch (\Throwable $exception) {
            throw $exception;
        }
        return $result;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Throwable
     */
    public function __call($name, $arguments)
    {
        try {
            ini_set('default_socket_timeout', -1);  //  read  block mode
            $result = $this->connetion->{$name}(...$arguments);
        } catch (\Throwable $throwable) {
            $result = $this->retry($name, $arguments, $throwable);
        }

        return $result;
    }

}