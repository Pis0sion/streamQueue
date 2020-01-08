<?php

require "./vendor/autoload.php";

//  定义stream 名称
const STREAM = "RegisterUsers";
//  定义msg的分组
const MSGGROUP = "group-send-message";
//  定义sms的消费者
const CONSUMER = "sms-consumer-1";

/**
 * Class SmsService
 */
class SmsService extends \Pis0sion\redis\core\BaseStreamService
{
    /**
     * @inheritDoc
     */
    protected function doProcess(array $message)
    {
        // TODO: Implement doProcess() method.
        $key = key($message);
        sleep(1);
        echo "执行发送信息到" . $message[$key]['mobile'] . " 成功......." . PHP_EOL;
        $this->ack($key);
    }
}

$smsService = new SmsService();

$smsService->process(STREAM, MSGGROUP, CONSUMER);
