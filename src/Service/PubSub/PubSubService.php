<?php

namespace Devtvn\Social\Service\PubSub;

use Devtvn\Social\Service\Contracts\PubSubContract;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;

class PubSubService implements PubSubContract
{
    protected $topic;

    public function __construct()
    {
    }

    public function send($messages): bool
    {
        if (is_array($this->topic)) {
            throw new \Exception("Topic must be string");
        }
        return Redis::publish($this->topic, json_encode($messages));
    }

    public function listen(\Closure $callback)
    {
        Redis::subscribe($this->topic,$callback);
    }

    public function topic($topic): PubSubContract
    {
        $this->topic = $topic;
        return $this;
    }
}