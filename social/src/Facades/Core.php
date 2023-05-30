<?php

namespace Core\Social\Facades;

use Core\Social\Service\Contracts\UserContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Core\Social\Service\Contracts\UserContract user()
 * @method static Core\Social\Service\Contracts\UserContract check()
 * @method static Core\Social\Service\Contracts\UserContract setUser(array $user)
 * @see Core\Social\Service\Contracts\UserContract
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
