<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Service\Contracts\PubSubContract;
use Illuminate\Support\Facades\Facade;
/**
 * @method static PubSubContract send($message)
 * @method static PubSubContract listen(\Closure $callback)
 * @method static PubSubContract topic(string|array $topic)
 * @see PubSubContract
 */
class PubSubCore extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PubSubContract::class;
    }
}