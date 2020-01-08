<?php

use Pis0sion\redis\core\BaseStreamService;

require "./vendor/autoload.php";

const STREAM = "RegisterUsers";

const MAILGROUP = "group-send-email";   // 发信息组

const CONSUMER = "email-consumer-1";

/**
 * Class EmailService
 */
class EmailService extends BaseStreamService
{
    /**
     * @inheritDoc
     */
    protected function doProcess(array $message)
    {
        // TODO: Implement doProcess() method.
        $key = key($message);
        sleep(1);
        echo "执行发送信息到邮箱" . $message[$key]['email'] . " 成功......." . PHP_EOL;
        $this->ack($key);
    }

}

$emailService = new EmailService();

$emailService->process(STREAM, MAILGROUP, CONSUMER);



