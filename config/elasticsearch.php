<?php

return [

    'config' => [
        'host' => env('ES_HOST','localhost'),
        'port' => env('ES_PORT','9200'),
        'schema'=>env("ES_SCHEMA",'http'),
        'user' => env('ES_USER'),
        'pass' => env('ES_PASS'),
        'retries' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Index Name
    |--------------------------------------------------------------------------
    |
    | This is the index name that Elasticquent will use for all
    | Elasticquent models.
    */

    'default_index' => 'default',

];
