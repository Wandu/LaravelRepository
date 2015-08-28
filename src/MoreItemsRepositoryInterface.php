<?php
namespace Wandu\Laravel\Repository;

interface MoreItemsRepositoryInterface extends RepositoryInterface
{
    /**
     * @return \Wandu\Laravel\Repository\DataMapper\Datamapper
     */
    public function getFirstItem();

    /**
     * @param string $itemId
     * @param int $length
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function getNextItems($itemId, $length = 10);

    /**
     * @param string $itemId
     * @param int $length
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function getPrevItems($itemId, $length = 10);
}
