<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\NotDefinedModelException;
use Wandu\Laravel\Repository\NotDefinedOrderByException;

trait RepositoryTrait
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
        return forward_static_call(([$this->getAttributeModel(), 'create']), $dataSet);
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
        $model = $this->getAttributeModel();
        return new $model;
    }

    /**
     * @param Builder $query
     * @param bool $reversed
     * @return Builder
     */
    protected function applyOrderBy(Builder $query, $reversed = false)
    {
        foreach ($this->getAttributeOrderBy() as $key => $asc) {
            $query = $query->orderBy($key, $asc ^ $reversed ? 'ASC' : 'DESC');
        }
        return $query;
    }

    /**
     * @return string
     */
    protected function getAttributeModel()
    {
        if (isset($this->model) && class_exists($this->model)) {
            return $this->model;
        }
        throw new NotDefinedModelException;
    }

    /**
     * @return array
     */
    protected function getAttributeOrderBy()
    {
        if (isset($this->orderBy) && is_array($this->orderBy)) {
            return $this->orderBy;
        }
        throw new NotDefinedOrderByException;
    }
}
