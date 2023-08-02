<?php

namespace Devtvn\Social\Facades;

use Devtvn\Social\Service\Contracts\ElasticSearchContract;
use Devtvn\Social\Service\ElasticSearch\ElasticSearchService;
use Illuminate\Support\Facades\Facade;

/**
 * @method   ElasticSearchContract createIndex():bool;
 * @method  static ElasticSearchContract setIndex(string $index):ElasticSearchContract;
 * @method  static ElasticSearchContract getAllIndex():array;
 * @method   ElasticSearchContract getIndex():string;
 * @method  ElasticSearchContract deleteIndex():bool;
 * @method static ElasticSearchContract deleteAllIndex():bool;
 * @see ElasticSearchService
 */
class ElasticSearch extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ElasticSearchContract::class;
    }
}