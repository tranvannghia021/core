<?php

namespace Devtvn\Social\Repositories;

use Devtvn\Social\Models\Core;

class CoreRepository extends BaseRepository
{
    public function __construct(Core $core)
    {
        parent::__construct($core);
    }

    /**
     * @override
     * @param $conditions
     * @param $select
     * @return array|mixed
     */
    public function findBy($conditions , $select = ["*"] ){
       return coreArray($this->model->select($select)->where($conditions)->first());
    }
}
