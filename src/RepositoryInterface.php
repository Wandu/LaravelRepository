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
