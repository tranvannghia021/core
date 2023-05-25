<?php

namespace App\Facades;

use App\Service\Contracts\UserContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static App\Service\Contracts\UserContract user()
 * @method static App\Service\Contracts\UserContract check()
 * @method static App\Service\Contracts\UserContract setUser(array $user)
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
