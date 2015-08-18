<?php
namespace Wandu\Laravel\Repository\Caching;

use Wandu\Laravel\Repository\PaginationRepositoryInterface;

class CachedPaginationRepository extends CachedRepository implements PaginationRepositoryInterface
{
    /** @var PaginationRepositoryInterface */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function getItems($skip = 0, $take = 10)
    {
        $key = $this->getItemsKey($skip, $take);
        if (!$this->cache->has($key)) {
            $items = $this->repository->getItems($skip, $take);
            $this->cache->put($key, $items, $this->minutes);

            // save take :-)
            $takes = $this->getCachedTakes();
            if (!in_array($take, $takes)) {
                $takes[] = $key;
                $this->cache->forever("{$this->prefix}.takes", $takes);
            }
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        parent::deleteItem($id);
        $takes = $this->getCachedTakes();
        foreach ($takes as $take) {
            $this->cache->pull($take);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        parent::updateItem($id, $dataSet);
        $takes = $this->getCachedTakes();
        foreach ($takes as $take) {
            $this->cache->pull($take);
        }
    }

    /**
     * @return array
     */
    protected function getCachedTakes()
    {
        return $this->cache->get($this->getTakesKey()) ?: [];
    }

    /**
     * @param int $skip
     * @param int $take
     * @return string
     */
    protected function getItemsKey($skip = 0, $take = 10)
    {
        return "{$this->prefix}.items.{$skip}.{$take}";
    }

    /**
     * @return string
     */
    protected function getTakesKey()
    {
        return "{$this->prefix}.takes";
    }
}
