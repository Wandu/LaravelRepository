<?php
namespace Wandu\Laravel\Repository;

interface MoreItemsRepositoryInterface extends RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFirstItem();

    /**
     * @param string $itemId
     * @param int $length
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNextItems($itemId, $length = 10);

    /**
     * @param string $itemId
     * @param int $length
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPrevItems($itemId, $length = 10);
}
