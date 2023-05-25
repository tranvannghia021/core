<?php

namespace App\Service\SocialPlatform;

use App\Service\ICoreService;

class TiktokService implements ICoreService
{

    public function setVariable(array $variable): ICoreService
    {
       return $this;
    }

    public function generateUrl(array $payload)
    {
        // TODO: Implement generateUrl() method.
    }

    public function auth(array $payload)
    {
        // TODO: Implement auth() method.
    }
}
