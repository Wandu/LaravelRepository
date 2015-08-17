<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;

interface PaginationRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $skip
     * @param int $take
     * @return Collection
     */
    public function getItems($skip = 0, $take = 10);
}
