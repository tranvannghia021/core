<?php

namespace Devtvn\Social\Service\Contracts;

interface PubSubContract
{
    /**
     * @param $messages
     * @return bool
     */
    public function send($messages):bool;

    /**
     * @param \Closure $callback
     * @return mixed
     */
    public function listen(\Closure $callback);

    /**
     * @param  $topic
     * @return PubSubContract
     */
    public function topic($topic):PubSubContract;
}