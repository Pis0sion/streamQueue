<?php


use Pis0sion\redis\entity\Streams;

require "./vendor/autoload.php";

const STREAM = "RegisterUsers";

$stream = new Streams();

for ($i = 5; $i--;) {
    $message = [
        'username' => uniqid(),
        'mobile' => rand(100000, 199999) . rand(10000, 99999),
        'email' => uniqid() . "@126.com",
    ];

//  创建并且塞入新用户队列
    $stream->push(STREAM, $message);
}

echo "执行完毕。。。。。。";



