<?php
namespace Wandu\Laravel\Repository\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\NotDefinedModelException;
use Wandu\Laravel\Repository\RepositoryInterface;

abstract class DatabaseRepository implements RepositoryInterface
{
    /** @var string */
    protected $model;

    /** @var array */
    protected $orderBy = [];

    /**
     * @return Model
     */
    public function getFirstItem()
    {
        return $this->applyOrderBy($this->createQuery())->first();
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getNextItems($itemId, $length = 10)
    {
        return $this->applyWhere(
            $this->applyOrderBy($this->createQuery()),
            $this->getItem($itemId)
        )->take($length)->get();
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getPrevItems($itemId, $length = 10)
    {
        return $this->applyWhere(
            $this->applyOrderBy($this->createQuery(), true),
            $this->getItem($itemId),
            true
        )->take($length)->get()->reverse();
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
            $item->update($dataSet);
        }
        return $item;
    }

    /**
     * @param array $dataSet
     * @return Model
     */
    public function createItem(array $dataSet)
    {
        return forward_static_call(([$this->model, 'create']), $dataSet);
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
     * @param Builder $query
     * @param bool $reversed
     * @return Builder
     */
    protected function applyOrderBy(Builder $query, $reversed = false)
    {
        foreach ($this->orderBy as $key => $asc) {
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
        foreach ($this->orderBy as $key => $asc) {
            $query = $query->where($key, $asc ^ $reversed ? '>' : '<', $base[$key]);
        }
        return $query;
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
}
