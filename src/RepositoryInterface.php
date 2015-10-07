<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findItems(array $where);

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllItems();

    /**
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getItem($id);

    /**
     * @param array $arrayOfId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsById(array $arrayOfId);

    /**
     * @param string $id
     * @param array $dataSet
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateItem($id, array $dataSet);

    /**
     * @param array $dataSet
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createItem(array $dataSet);

    /**
     * @param string $id
     */
    public function deleteItem($id);

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function cacheItem(Model $item);

    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cacheItems(Collection $items);

    /**
     * @param string $id
     */
    public function flushItem($id);
}
