<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\DataMapper\Collection;
use Wandu\Laravel\Repository\DataMapper\Mappable;

abstract class Repository implements RepositoryInterface, Mappable
{
    /** @var string */
    protected $model;

    /** @var array */
    protected $orders = [
        'id' => false,
    ];

    /**
     * {@inheritdoc}
     */
    abstract public function toMapper(Model $class);

    /**
     * {@inheritdoc}
     */
    public function toMappers(EloquentCollection $collection)
    {
        $collectionToReturn = [];
        $collection->each(function (Model $model) use (&$collectionToReturn) {
            $collectionToReturn[] = $this->toMapper($model);
        });
        return new Collection($collectionToReturn);
    }

    /**
     * {@inheritdoc}
     */
    public function findItems(array $where)
    {
        $query = $this->createQuery();
        foreach ($where as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $this->toMappers($query->get());
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        return $this->toMappers($this->createQuery()->get());
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        $item = $this->createQuery()->find($id);
        return isset($item) ? $this->toMapper($item) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsById(array $arrayOfId)
    {
        return $this->toMappers($this->createQuery()->whereIn($this->createModel()->getKeyName(), $arrayOfId)->get());
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        $this->createQuery()->where($this->getKeyName(), $id)->update($dataSet);
        return $this->getItem($id);
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(array $dataSet)
    {
        return $this->toMapper(forward_static_call(([$this->getAttributeModel(), 'create']), $dataSet));
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $this->createQuery()->where($this->createModel()->getKeyName(), $id)->delete();
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
