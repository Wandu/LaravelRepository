<?php
namespace Wandu\Laravel\Repository;

interface PaginationRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $skip
     * @param int $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItems($skip = 0, $take = 10);
}
