<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Service\Contracts\CoreContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Devtvn\Social\Service\Contracts\CoreContract user()
 * @method static \Devtvn\Social\Service\Contracts\CoreContract check()
 * @method static \Devtvn\Social\Service\Contracts\CoreContract setUser(array $user)
 * @see \Devtvn\Social\Service\Contracts\CoreContract
 *
 */
class Core extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CoreContract::class;
    }
}
