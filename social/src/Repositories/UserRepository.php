<?php

namespace Core\Social\Repositories;

use Core\Social\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
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
