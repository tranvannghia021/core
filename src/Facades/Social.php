<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Ecommerces\Ecommerces;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Devtvn\Social\Ecommerces\Ecommerces driver(string $channel)
 * @see \Devtvn\Social\Ecommerces\Ecommerces
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