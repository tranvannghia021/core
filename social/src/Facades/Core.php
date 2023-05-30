<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Service\Contracts\UserContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Devtvn\Social\Service\Contracts\UserContract user()
 * @method static Devtvn\Social\Service\Contracts\UserContract check()
 * @method static Devtvn\Social\Service\Contracts\UserContract setUser(array $user)
 * @see Devtvn\Social\Service\Contracts\UserContract
 *
 */
class Core extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UserContract::class;
    }
}
