<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Collection;

trait PaginationRepositoryTrait
{
    use CrudRepositoryTrait;

    /**
     * @param int $skip
     * @param int $take
     * @return Collection
     */
    public function getItems($skip = 0, $take = 10)
    {
        return $this->applyOrderBy($this->createQuery())->skip($skip)->take($take)->get();
    }
}
