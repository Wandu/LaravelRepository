<?php
namespace Wandu\Laravel\Repository;

interface RepositoryInterface
{
    /**
     * @param array $where
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function findItems(array $where);

    /**
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function getAllItems();

    /**
     * @param string $id
     * @return \Wandu\Laravel\Repository\DataMapper\DataMapper|null
     */
    public function getItem($id);

    /**
     * @param array $arrayOfId
     * @return \Wandu\Laravel\Repository\DataMapper\Collection
     */
    public function getItemsById(array $arrayOfId);

    /**
     * @param string $id
     * @param array $dataSet
     * @return \Wandu\Laravel\Repository\DataMapper\DataMapper
     */
    public function updateItem($id, array $dataSet);

    /**
     * @param array $dataSet
     * @return \Wandu\Laravel\Repository\DataMapper\DataMapper
     */
    public function createItem(array $dataSet);

    /**
     * @param string $id
     */
    public function deleteItem($id);
}
