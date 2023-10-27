<?php

namespace App\Services;

use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    protected Model $model;

    abstract protected function getModel() : Model ;

    /**deleteWhere
     * Repository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function __call(string $method, array $args): mixed
    {
       return call_user_func_array([$this->model, $method], $args);
    }

    public function updateByFields(array $data, array $conditions)
    {
        foreach($conditions as $key => $value) {
            $this->model = $this->model->where($key, $value);
        }

        return $this->model->update($data);
    }


    /**
     * @return mixed
     */
    public function first(): ?Model
    {
        return $this->model->first();
    }

    /**
     * @return mixed
     */
    public function last(): ?Model
    {
        return $this->model->latest()->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createMultiple(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @return mixed
     */
    public function getTrashed()
    {
        return $this->model->withTrashed();
    }

    /**
     * @param $primaryKey
     * @return mixed
     */
    public function find($primaryKey)
    {
        return $this->model->find($primaryKey);
    }

    /**
     * @param array $conditions
     * @param array $select
     * @return mixed
     */
    public function findWhere(array $conditions, $select = ['*'])
    {
        $this->newInstance();
        foreach ($conditions as $key => $value ) {

            if (is_array($value)) {
                $this->model->where($value[0], $value[1], $value[2]);
            }else{
                $this->model->where($key, $value);
            }
        }

        return $this->model->select($select);
    }
    public function findWhereFirst(array $data)
    {
        return $this->model->where($data)->first();
    }


    /**
     * @param array $conds
     * @param array $select
     * @return mixed
     */
    public function getWhere($conds = [], $select = ['*'])
    {
        foreach ($conds as $key=>$val) {
            if (is_array($val)) {
                $data = $this->model->where($val[0], $val[1], $val[2]);
            }else{
                $data = $this->model->where($key, $val);
            }
        }
        $data = $this->model->get($select);
        return $data;
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function findFirst(array $conditions)
    {
        $data = $this->model->where($conditions)->first();
        return $data;
    }

    public function exists($primaryKey)
    {
        $this->find($primaryKey)->exists();

        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getInsertedId(array $data)
    {
        return $this->model->insertGetId($data);
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->model;
    }

    /**
     * @param int $itemPerPage
     * @return mixed
     */
    public function paginate(int $itemPerPage = 20)
    {
        return $this->model
            ->paginate($itemPerPage);
    }

    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function searchByColumn(string $column, string $value)
    {
        return $this->model
            ->where($column ,'LIKE','%' . $value . '%')
            ->get();
    }


    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function searchWhere(string $column, string $value)
    {
        return $this->model
            ->where($column , $value )
            ->first();
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->model->getKeyName();
    }

    public function whereWithMultiple(array $multipleConditions)
    {
        return $this->model->where($multipleConditions)->get();
    }


    public function updateOrCreate(array $findValue,array $data)
    {
        return $this->model->updateOrCreate($findValue, $data);
    }
}
