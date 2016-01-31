<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    /** @var string */
    protected $model;

    /** @var array */
    protected $orders = [
        'id' => false,
    ];

    /** @var array */
    protected $cached = [];

    /** @var bool */
    protected $cacheEnabled = true;

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->createQuery()->count();
    }

//    /**
//     * @param array $items
//     */
//    public function insertItems(array $items)
//    {
//        if(!isArrayOfArray($items)) {
//            throw new InvalidArgumentException('is not array of array');
//        }
//        if (count($items) === 0) {
//            return;
//        }
//
//        // 10개 미만일 때는 그냥 넣으면 됨. *-_-*..
//        if (count($items) <= 10) {
//            $this->createQuery()->insert($items);
//            return;
//        }
//
//        foreach (array_chunk($items, 10) as $chunkedItems) {
//            $this->createQuery()->insert($chunkedItems);
//        }
//    }

    /**
     * {@inheritdoc}
     */
    public function findItems(array $where)
    {
        $query = $this->createQuery();
        foreach ($this->filterWhere($where) as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $this->cacheItems($query->get());
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        return $this->cacheItems($this->applyScopeOrders($this->createQuery())->get());
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        if (!isset($this->cached[$id])) {
            $item = $this->createQuery()->find($id);
            if (isset($item)) {
                $this->cacheItem($item);
            }
        }
        return isset($this->cached[$id]) ? $this->cached[$id] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsById(array $arrayOfId)
    {
        $keyName = $this->getKeyName();
        $items = $this->createQuery()->whereIn($keyName, $arrayOfId)->get();
        return $this->cacheItems($items);
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        $item = $this->getItem($id)->fill($dataSet);
        $item->save();
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(array $dataSet)
    {
        return $this->cacheItem(
            forward_static_call(([$this->getAttributeModel(), 'create']), $this->filterDataSet($dataSet))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $this->createQuery()->where($this->createModel()->getKeyName(), $id)->delete();
        $this->flushItem($id);
    }

    /**
     * {@inheritdoc}
     */
    public function cacheItem(Model $item)
    {
        if ($this->cacheEnabled) {
            $this->cached[$item->getKey()] = $item;
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function cacheItems(Collection $items)
    {
        if ($this->cacheEnabled) {
            foreach ($items as $item) {
                $this->cacheItem($item);
            }
        }
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function flushItem($id)
    {
        unset($this->cached[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function flushAllItems()
    {
        $this->cached = [];
    }

    /**
     * {@inheritdoc}
     */
    public function cacheEnable($cacheEnabled = true)
    {
        $lastCacheEnabled = $this->cacheEnabled;
        $this->cacheEnabled = $cacheEnabled;
        return $lastCacheEnabled;
    }

    /**
     * @param array $dataSet
     * @return array
     */
    public function filterDataSet(array $dataSet)
    {
        return $dataSet;
    }

    /**
     * @param array $where
     * @return array
     */
    public function filterWhere(array $where)
    {
        return $where;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $reversed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopeOrders(Builder $query, $reversed = false)
    {
        foreach ($this->orders as $key => $asc) {
            $query = $query->orderBy($key, $asc ^ $reversed ? 'ASC' : 'DESC');
        }
        return $query;
    }

    /**
     * @return string
     */
    protected function getKeyName()
    {
        static $keyName;
        if (!isset($keyName)) {
            $keyName = $this->createModel()->getKeyName();
        }
        return $keyName;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function createQuery()
    {
        return $this->createModel()->newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createModel()
    {
        $model = $this->getAttributeModel();
        return new $model;
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
}
