<p align="center"><a href="#" target="_blank"><img src="https://i.postimg.cc/tTssS34W/package-core.png" width="400"></a></p>
<p style="align-items: center; margin:5px auto;display: flex;justify-content: center">Social authentication multiple-platform and management users</p>

## feature
- Facebook
- Google
- Tiktok(maintenance)
- Instagram
- Twitter
- Github
- Linkedin
- Bitbucket
- GitLab
## Official Core SDKs
<div>
<ul>
    <li><a href="https://github.com/tranvannghia021/core">Php</a></li>
</ul>
</div>


## Required
- Php >= 7.4
- Laravel >= 8.x 
- Composer >= 2.x
## Install
```bash
composer require devtvn/social
```
## Setup
-    Add the config behind in the file kernel.php

```php
protected $middlewareGroups = [
   ...

        'api' => [
           ...
            \Devtvn\Social\Http\Middleware\GlobalJwtMiddleware::class
        ],
    ];
```


 - Add the config behind in the file config database.php

```php
 <?php
  'connections' => [
    ...
     'database_core' => [
                'driver' => 'pgsql',
                'url' => env('DATABASE_CORE_URL'),
                'host' => env('DB_CORE_HOST', 'postgres'),
                'port' => env('DB_CORE_PORT', '5432'),
                'database' => env('DB_CORE_DATABASE', 'core'),
                'username' => env('DB_CORE_USERNAME', 'default'),
                'password' => env('DB_CORE_PASSWORD', 'secret'),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
    
            ],
    ...
]
```
- If you want customs model users then add config behind
```php
use Devtvn\Social\Models\User as ModelsUse;
class User extends ModelsUser
{  
}

```
- And remove file user migration 

```php
xxxx_xx_xx_xxxxxx_create_users_table.php
```
## After setup config completed :
- Run command in terminal:
```bash
php artisan vendor:publish --tag=core-social && php artisan migrate
```
- Setup worker:
```bash
php artisan queue:work {onconnection in file social.php} --queue={onqueue in file social.php} --sleep=3 --tries=3 --timeout=9000
```

## MIT
