<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Ecommerces\RestApi\Ecommerces;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Devtvn\Social\Ecommerces\RestApi\Ecommerces driver(string $channel)
 * @see Devtvn\Social\Ecommerces\RestApi\Ecommerces
 */
class Social extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Ecommerces::class;
    }
}