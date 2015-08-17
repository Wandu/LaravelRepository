<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\NotDefinedModelException;
use Wandu\Laravel\Repository\NotDefinedOrderByException;

trait CrudRepositoryTrait
{
    /**
     * @param array $where
     * @return Collection
     */
    public function findItems(array $where)
    {
        $query = $this->createQuery();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->get();
    }

    /**
     * @return Collection
     */
    public function getAllItems()
    {
        return $this->createQuery()->get();
    }

    /**
     * @param string $id
     * @return Model|null
     */
    public function getItem($id)
    {
        return $this->createQuery()->find($id);
    }

    /**
     * @param string $id
     * @param array $dataSet
     * @return Model
     */
    public function updateItem($id, array $dataSet)
    {
        if ($item = $this->getItem($id)) {
            $this->createQuery()->where($item->getKeyName(), $id)->update($dataSet);
            foreach ($dataSet as $key => $value) {
                $item[$key] = $value;
            }
        }
        return $item;
    }

    /**
     * @todo to use data mapper (not active record)
     *
     * @param array $dataSet
     * @return Model
     */
    public function createItem(array $dataSet)
    {
        if (isset($this->model)) {
            return forward_static_call(([$this->model, 'create']), $dataSet);
        }
        throw new NotDefinedModelException;
    }

    /**
     * @param string $id
     */
    public function deleteItem($id)
    {
        if ($item = $this->getItem($id)) {
            $item->delete();
        }
    }

    /**
     * @return Builder
     */
    protected function createQuery()
    {
        return $this->createModel()->newQuery();
    }

    /**
     * @return Model
     */
    protected function createModel()
    {
        if (isset($this->model)) {
            return new $this->model;
        }
        throw new NotDefinedModelException;
    }

    /**
     * @param Builder $query
     * @param bool $reversed
     * @return Builder
     */
    protected function applyOrderBy(Builder $query, $reversed = false)
    {
        foreach ($this->getOrderBy() as $key => $asc) {
            $query = $query->orderBy($key, $asc ^ $reversed ? 'ASC' : 'DESC');
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param Model $base
     * @param bool $reversed
     * @return Builder
     */
    protected function applyWhere(Builder $query, Model $base, $reversed = false)
    {
        // @todo
        foreach ($this->getOrderBy() as $key => $asc) {
            $query = $query->where($key, $asc ^ $reversed ? '>' : '<', $base[$key]);
        }
        return $query;
    }

    /**
     * @return array
     */
    protected function getOrderBy()
    {
        if (isset($this->orderBy)) {
            return $this->orderBy;
        }
        throw new NotDefinedOrderByException;
    }
}
