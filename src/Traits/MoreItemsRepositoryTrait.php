<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MoreItemsRepositoryTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFirstItem()
    {
        return $this->applyScopeFirstItem($this->createQuery())->first();
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNextItems($itemId, $length = 10)
    {
        return $this->applyScopeNextItems(
            $this->createQuery(),
            $this->createQuery()->find($itemId)
        )->take($length)->get();
    }

    /**
     * @param string $itemId
     * @param int $length
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPrevItems($itemId, $length = 10)
    {
        return $this->applyScopePrevItems(
            $this->createQuery(),
            $this->createQuery()->find($itemId)
        )->take($length)->get()->reverse();
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
