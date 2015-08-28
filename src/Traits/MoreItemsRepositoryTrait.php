<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait MoreItemsRepositoryTrait
{
    /**
     * @return \Wandu\Laravel\Repository\DataMapper\Datamapper
     */
    public function getFirstItem()
    {
        $item = $this->applyScopeFirstItem($this->createQuery())->first();
        return isset($item) ? $this->toMapper($item) : null;
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getNextItems($itemId, $length = 10)
    {
        return $this->toMappers($this->applyScopeNextItems(
            $this->createQuery(),
            $this->createQuery()->find($itemId)
        )->take($length)->get());
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getPrevItems($itemId, $length = 10)
    {
        return $this->toMappers($this->applyScopePrevItems(
            $this->createQuery(),
            $this->createQuery()->find($itemId)
        )->take($length)->get()->reverse());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopeFirstItem(Builder $query)
    {
        return $this->applyScopeOrders($query);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopeNextItems(Builder $query, Model $criteria)
    {
        return $this->applyScopeOrders($query)
            ->where($criteria->getKeyName(), '<', $criteria->getKey());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopePrevItems(Builder $query, Model $criteria)
    {
        return $this->applyScopeOrders($query, true)
            ->where($criteria->getKeyName(), '>', $criteria->getKey());
    }
}
