<?php

namespace Devtvn\Social\Ecommerces;

class Ecommerces
{
    public static function driver($channel): ?AEcommerce
    {
        $nameSpaces = '\Devtvn\Social\Ecommerces\RestApi\\';
        $path = __DIR__ . '/RestApi/';
        $files = array_diff(scandir($path), ['..', '.', 'Request.php']);
        foreach ($files as $file) {
            if ($file === ucfirst($channel) . '.php') {
                $class = $nameSpaces . trim($file, '.php');
                return (new $class());
            }
        }
        return null;
    }
}