<?php

namespace Devtvn\Social\Service\Contracts;

interface ElasticSearchContract
{
    public function createIndex();

    public function setIndex(string $index):ElasticSearchContract;

    public function getIndex():string;

    public function deleteIndex():bool;

    public function deleteAllIndex():bool;

    public function getAllIndex():array;

    public function setType(string $type):ElasticSearchContract;

    public function getType():string;

    public function createDocument(array $data);

    public function getAllDocument(int $page,int $limit):array;

    public function updateDocument(string $id,array $data):array;

    public function deleteDocument(string $id):bool;

    public function deleteAllDocument():bool;

    public function info():array;

    public function search(array $query):array;
}
