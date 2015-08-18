<?php
namespace Wandu\Laravel\Repository\Caching;

use Wandu\Laravel\Repository\MoreItemsRepositoryInterface;

class CachedMoreItemsRepository extends CachedRepository implements MoreItemsRepositoryInterface
{
    /** @var CachedMoreItemsRepository */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function getFirstItem()
    {
        return $this->repository->getFirstItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getNextItems($itemId, $length = 10)
    {
        $key = $this->getItemKey($itemId) . ".next";
        if (!$this->cache->has($key)) {
            $items = $this->repository->getNextItems($itemId, $length + 1);
            if (count($items) <= $length) {
                return $items;
            }
            $items = $items->slice(0, 10);
            $this->cache->put($key, $items, 10);
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrevItems($itemId, $length = 10)
    {
        $key = $this->getItemKey($itemId) . ".prev";
        if (!$this->cache->has($key)) {
            $items = $this->repository->getPrevItems($itemId, $length + 1);
            if (count($items) <= $length) {
                return $items;
            }
            $this->cache->put($key, $items->slice(1), 10);
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        $item = parent::updateItem($id, $dataSet);
        $this->pullListCacheArond($item[$item->getKeyName()]);
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $this->pullListCacheArond($id);
        parent::deleteItem($id);
    }

    /**
     * @param string $id
     */
    protected function pullListCacheArond($id)
    {
        foreach ($this->getPrevItems($id) as $item) {
            $this->cache->pull($this->getItemKey($item[$item->getKeyName()]) .'.next');
        }
        foreach ($this->getNextItems($id) as $item) {
            $this->cache->pull($this->getItemKey($item[$item->getKeyName()]) .'.prev');
        }
    }
}
