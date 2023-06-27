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
- Microsoft
- Dropbox
- Reddit
- Pinterest(maintenance)
- Line
- shopify
## Official Core SDKs
<div>
<ul>
    <li><a href="https://github.com/tranvannghia021/core">Php</a></li>
    <li><a href="https://github.com/tranvannghia021/gocore">GoLang</a></li>
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
- If you want customs model core then add config behind
```php
use Devtvn\Social\Models\Core;
class User extends Core
{  
}

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
- Setup redirect_uri in app developer :
```bash
  {host}/api/handle/auth 
```
## API

| Method | URI                         | Action                             | Middleware                       |
|--------|-----------------------------|------------------------------------|----------------------------------|
| POST   | api/{platform}/generate-url | CoreController@generateUrl         | global                           |
| GET    | api/handle/auth             | CoreController@handleAuth          | social.auth,core.shopify,global  |
| POST   | api/app/login               | AppController@login                | global                           |
| POST   | api/app/register            | CoreController@register            | global                           |
| POST   | api/app/refresh             | CoreController@refresh             | refresh                          |
| DELETE | api/app/delete              | CoreController@delete              | core ,global                     |
| GET    | api/app/info                | CoreController@user                | core ,global                     |
| POST   | api/app/info                | CoreController@updateUser          | core  ,global                    |
| PUT    | api/app/change-password     | CoreController@changePassword      | core ,global                     |
| POST   | api/app/forgot-password     | CoreController@reset               | global                           |
| POST   | api/app/re-send             | CoreController@reSendLinkEmail     | global                           |


## MIT
