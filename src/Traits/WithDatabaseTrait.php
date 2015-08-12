<?php
namespace Wandu\Laravel\Repository\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait WithDatabaseTrait
{
    /**
     * @return Model
     */
    public function getFirstItem()
    {

    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getNextItems($itemId, $length = 10)
    {

    }

    /**
     * @param string $itemId
     * @param int $length
     * @return Collection
     */
    public function getPrevItems($itemId, $length = 10)
    {

    }
//
//    /**
//     * @param int $limit
//     * @return mixed
//     */
//    public function getItems($limit = -1)
//    {
//        if ($limit === -1) {
//            return forward_static_call([$this->model, 'paginate']);
//        }
//        return forward_static_call([$this->model, 'paginate'],$limit);
//    }

    /**
     * @param string $id
     * @return Model|null
     */
    public function getItem($id)
    {
        return forward_static_call([$this->model, 'find'], $id);
    }

    /**
     * @param string $id
     * @param array $dataSet
     * @return Model
     */
    public function updateItem($id, array $dataSet)
    {
        if ($item = forward_static_call([$this->model, 'find'], $id)) {
            $item->update($dataSet);
        }
        return $item;
    }

    /**
     * @param array $dataSet
     * @return Model
     */
    public function createItem(array $dataSet)
    {
        return forward_static_call(([$this->model, 'create']), $dataSet);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteItem($id)
    {
        return forward_static_call(([$this->model, 'where']), ['uid' => $id])->delete();
    }
}
