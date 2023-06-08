<?php

namespace Devtvn\Social\Service;

class CoreService
{
    public static function setChannel(string $channel, array $variable = []): ?ACoreService
    {
        $nameSpaces = '\Devtvn\Social\Service\SocialPlatform\\';
        $path = __DIR__ . '/SocialPlatform/';
        $files = array_diff(scandir($path), ['..', '.']);
        foreach ($files as $file) {
            if ($file === ucfirst($channel) . 'Service.php') {
                $class = $nameSpaces . trim($file, '.php');
                return (new $class())->setVariable($variable);
            }
        }
        return null;
    }
}
