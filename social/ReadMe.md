### Package manager users multiple platform (facebook,google,tiktok,inApp)

### add config in http/kennel.php ->middlewareGroups->api
```json
Core\Social\Http\Middleware\GlobalJwtMiddleware::class
```
```json
run command :
php artisan vendor:publish --tag=core-social
```

```text
addition database.php config
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

```

```text
remove file user migration in database/migrations/...
```

```json
php artisan migrate --path=/vendor/core/social/migtations
```

```json
create file User in model
use Core\Social\Models\User as ModelsUse;
class User extends ModelsUser
{  
}
```

```json
run worker
php artisan queue:work {onconnection in file social.php} --queue={onqueue in file social.php} --sleep=3 --tries=3 --timeout=9000
```