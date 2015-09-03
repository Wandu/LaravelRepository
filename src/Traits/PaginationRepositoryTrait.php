<?php
namespace Wandu\Laravel\Repository\Traits;

trait PaginationRepositoryTrait
{
    /**
     * @param int $skip
     * @param int $take
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function getItems($skip = 0, $take = 10)
    {
        return $this->toMappers(
            $this->applyScopeOrders($this->createQuery())->skip($skip)->take($take)->get()
        );
    }
}
