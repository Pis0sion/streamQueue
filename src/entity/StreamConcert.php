<?php


namespace Pis0sion\redis\entity;


use Pis0sion\redis\concert\IStreamQueueInterface;

/**
 * Class StreamConcert
 * @package Pis0sion\redis\entity
 */
abstract class StreamConcert implements IStreamQueueInterface
{
    /**
     * @var
     */
    protected $channel ;

    /**
     * StreamConcert constructor.
     */
    public function __construct()
    {
        $this->channel =  $this->initChannel() ;
    }

    /**
     * 实例化channel
     * @return mixed
     */
    abstract function initChannel() ;

}