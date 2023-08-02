<?php

namespace Devtvn\Social\Service\ElasticSearch;

use Devtvn\Social\Service\Contracts\ElasticSearchContract;
use Elasticsearch\ClientBuilder;
use Mockery\Exception;

class ElasticSearchService implements ElasticSearchContract
{
    protected $client;
    protected string $index;
    protected string $type = "type_core";
    private int $limit = 10;
    private int $page = 1;

    public function __construct()
    {
        $hosts = [
            [
                'host' => config('elasticsearch.config.host'),
                'port' => config('elasticsearch.config.port'),
                'schema' => config('elasticsearch.config.schema'),
                'user' => config('elasticsearch.config.user'),
                'pass' => config('elasticsearch.config.pass'),
            ]
        ];

        $this->client = ClientBuilder::create()->setRetries(config('elasticsearch.config.retries'))->setHosts($hosts)->build();
    }

    public function info(): array
    {
        return $this->client->info();
    }

    public function createIndex()
    {
        try {
            if (!isset($this->index)) {
                throw new Exception("Index is required");
            }
            $params = [
                'index' => $this->index,
            ];
            return $this->client->indices()->create($params);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function setIndex(string $index): ElasticSearchContract
    {
        $this->index = $index;
        return $this;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function deleteIndex(): bool
    {
        try {
            if (!isset($this->index)) {
                throw new Exception("Index is required");
            }
            $params = [
                'index' => $this->index,
            ];
            $response = $this->client->indices()->delete($params);
            return @$response['acknowledged'];
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function deleteAllIndex(): bool
    {
        try {
            $list = $this->getAllIndex();
            foreach ($list as $index) {
                $params = [
                    'index' => $index,
                ];
                $this->client->indices()->delete($params);
            }
            return true;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getAllIndex(): array
    {
        return \Illuminate\Support\Arr::pluck($this->client->cat()->indices(), 'index');
    }

    public function setType(string $type): ElasticSearchContract
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function createDocument(array $data)
    {
        try {
            if (!isset($this->index)) {
                throw new Exception("Index is required");
            }
            $params = [
                'index' => $this->index,
                'type' => $this->type,
                'body' => $data
            ];
            return $this->client->index($params);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getAllDocument(int $page = 1, int $limit = 10): array
    {
        try {
            if (!isset($this->index)) {
                throw new Exception("Index is required");
            }
            if ($page <= 0) {
                $page = $this->page;
            }
            $this->limit = $limit;
            $params = [
                'index' => $this->index,
                'body' => [
                    'size' => $limit,
                    'from' => $this->limit * ($page - 1),
                ]
            ];
            $result = $this->client->search($params);
            $result['is_next'] = $this->handlePage($page, $result['hits']['total']['value']);
            return $result;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateDocument(string $id, array $data): array
    {
        if (!isset($this->index)) {
            throw new Exception("Index is required");
        }

        if (!isset($id)) {
            throw new Exception("Id is required");
        }
        if (!isset($this->type)) {
            throw new Exception("Type is required");
        }

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id,
            'body' => [
                'doc' => $data
            ]
        ];
        return $this->client->update($params);
    }

    public function deleteDocument(string $id): bool
    {
        if (!isset($this->index)) {
            throw new Exception("Index is required");
        }

        if (!isset($id)) {
            throw new Exception("Id is required");
        }

        if (!isset($this->type)) {
            throw new Exception("Type is required");
        }

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $id
        ];
        $result = $this->client->delete($params);
        return $result['result'] === "deleted";
    }

    public function deleteAllDocument(): bool
    {
        $this->deleteIndex();
        $this->createIndex();
        return true;
    }

    private function handlePage(int $page, int $total)
    {
        if ($page <= 0) {
            $page = $this->page;
        }
        $totalPage = ceil($total / $this->limit);
        if ($totalPage === 0) {
            $totalPage++;
        }

        return $page < $totalPage;
    }

    public function search(array $query = []): array
    {
        if (!isset($this->index)) {
            throw new Exception("Index is required");
        }

        if (!isset($this->type)) {
            throw new Exception("Type is required");
        }
        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'body' => [
                'query' => [
                    'match' => $query
                ]
            ]
        ];
        return $this->client->search($params);
    }
}