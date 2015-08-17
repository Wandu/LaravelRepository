<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
}
