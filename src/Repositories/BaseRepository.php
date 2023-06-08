<?php

namespace Devtvn\Social\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements IRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * getAll
     *
     * @return array|object
     */
    public function getAll()
    {
        return $this->model->all();
    }
    /**
     * find
     *
     * @param  mixed $id
     * @return object|array
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * create
     *
     * @param  mixed $data
     * @return  array|object
     */
    public function create(array $data)
    {
        try {
            $model = $this->model->create($data);
        } catch (\Exception $e) {
            throw $e;
        }
        return $model;
    }
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return object|array
     */
    public function update($id, array $data)
    {
        try {

            $model = $this->find($id);
            if ($model) {
                $model->update($data);
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $model;
    }
    /**
     * delete
     *
     * @param  mixed $id
     * @return bool
     */
    public function delete($id):bool
    {
        try {
            $model = $this->find($id);
            if ($model) {

                $model->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * updateOrInsert
     *
     * @param $conditions
     * @param array $attributes
     * @return object|array
     */
    public function updateOrInsert($conditions,array $attributes){
        return $this->model->updateOrInsert($conditions,$attributes)->first();
    }

    /**
     * @param $conditions
     * @param $select
     * @return mixed
     */
    public function findBy($conditions , $select = ['*']){
        return $this->model->where($conditions)->select($select)->first();
    }
}
