php artisan queue:work redis --queue=verify-email --sleep=3 --tries=3 --timeout=9000 --daemon > storage/logs/queue.txt 2>&1 &
