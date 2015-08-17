<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\NotDefinedOrderByException;

trait MoreItemRepositoryTrait
{
    use CrudRepositoryTrait;

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
