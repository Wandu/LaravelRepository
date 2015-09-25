<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Builder;

abstract class Repository implements RepositoryInterface
{
    /** @var \Wandu\Laravel\Repository\Repository */
    protected $parent;

    /** @var string */
    protected $model;

    /** @var array */
    protected $orders = [
        'id' => false,
    ];

    /**
     * @param \Wandu\Laravel\Repository\Repository $parent
     * @return self
     */
    public function setParent(Repository $parent)
    {
        $this->parent = $parent;
        return $this;
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
        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        return $this->createQuery()->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        return $this->createQuery()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsById(array $arrayOfId)
    {
        return $this->createQuery()->whereIn($this->createModel()->getKeyName(), $arrayOfId)->get();
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
        return forward_static_call(([$this->getAttributeModel(), 'create']), $dataSet);
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
