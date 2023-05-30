<?php

namespace Core\Social\Service\SocialPlatform;

use Core\Social\Service\ICoreService;

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
