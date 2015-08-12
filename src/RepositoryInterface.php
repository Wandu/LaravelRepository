<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
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

    /**
     * @param string $id
     * @return Model|null
     */
    public function getItem($id);

    /**
     * @param string $id
     * @param array $dataSet
     * @return Model
     */
    public function updateItem($id, array $dataSet);

    /**
     * @param array $dataSet
     * @return Model
     */
    public function createItem(array $dataSet);

    /**
     * @param string $id
     */
    public function deleteItem($id);
}
