<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserMoreItemsRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFirstItem()
    {
        return $this->applyScopeOrders($this->createQuery())->first();
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
     * @param \Illuminate\Database\Eloquent\Model $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopeNextItems(Builder $query, Model $criteria)
    {
        $orders = $this->orders;
        $query = $query->where(function (Builder $query) use ($orders, $criteria) {
            $queryOrders = [];
            foreach ($orders as $key => $order) {
                $query->orWhere(function (Builder $query) use ($key, $order, $queryOrders, $criteria) {
                    foreach ($queryOrders as $queryOrder) {
                        $query->where($queryOrder[0], $criteria[$queryOrder[0]]);
                    }
                    $query->where($key, $order ? '>' : '<', $criteria[$key]);
                });
                $queryOrders[] = [$key, $order];
            }
        });
        return $this->applyScopeOrders($query);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyScopePrevItems(Builder $query, Model $criteria)
    {
        $orders = $this->orders;
        $query = $query->where(function (Builder $query) use ($orders, $criteria) {
            $queryOrders = [];
            foreach ($orders as $key => $order) {
                $query->orWhere(function (Builder $query) use ($key, $order, $queryOrders, $criteria) {
                    foreach ($queryOrders as $queryOrder) {
                        $query->where($queryOrder[0], $criteria[$queryOrder[0]]);
                    }
                    $query->where($key, $order ? '<' : '>', $criteria[$key]);
                });
                $queryOrders[] = [$key, $order];
            }
        });
        return $this->applyScopeOrders($query, true);
    }
}
