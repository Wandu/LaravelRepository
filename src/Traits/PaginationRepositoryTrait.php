<?php
namespace Wandu\Laravel\Repository\Traits;

trait PaginationRepositoryTrait
{
    /**
     * @param int $skip
     * @param int $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItems($skip = 0, $take = 10)
    {
        return $this->applyScopeOrders($this->createQuery())->skip($skip)->take($take)->get();
    }
}
