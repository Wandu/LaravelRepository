<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array $where
     * @return Collection
     */
    public function findItems(array $where);

    /**
     * @return Collection
     */
    public function getAllItems();

    /**
     * @param string $id
     * @return Model|null
     */
    public function getItem($id);

    /**
     * @param array $arrayOfId
     * @return Collection
     */
    public function getItemsById(array $arrayOfId);

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
