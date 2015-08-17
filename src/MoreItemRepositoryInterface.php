<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface MoreItemRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Model
     */
    public function getFirstItem();

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getNextItems($itemId, $length = 10);

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getPrevItems($itemId, $length = 10);
}
