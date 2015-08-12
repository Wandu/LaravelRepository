<?php
namespace Wandu\Laravel\Repository\Decorator;

use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\RepositoryInterface;

class ObjectCacheDecorator implements RepositoryInterface
{
    protected $length = 10;

    /** @var RepositoryInterface */
    private $repository;

    /** @var CacheContract */
    private $cache;

    /** @var string */
    private $prefix;

    /**
     * @param RepositoryInterface $repository
     * @param CacheContract $cache
     * @param string $prefix
     */
    public function __construct(RepositoryInterface $repository, CacheContract $cache, $prefix = 'wandu')
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

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
    public function getNextItems($itemId, $length = null)
    {
        $key = $this->getKey($itemId) . ".next";
        if (!$this->cache->has($key)) {
            $items = $this->repository->getNextItems($itemId, $this->length + 1);
            if (count($items) <= $this->length) {
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
    public function getPrevItems($itemId, $length = null)
    {
        $key = $this->getKey($itemId) . ".prev";
        if (!$this->cache->has($key)) {
            $items = $this->repository->getPrevItems($itemId, $this->length + 1);
            if (count($items) <= $this->length) {
                return $items;
            }
            $this->cache->put($key, $items->slice(1), 10);
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        $key = $this->getKey($id);
        if (!$this->cache->has($key)) {
            $item = $this->repository->getItem($id);
            if (!$item) {
                return null;
            }
            $this->cache->put($key, $item, 10);
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        $key = $this->getKey($id);
        $item = $this->repository->updateItem($id, $dataSet);
        if ($this->cache->has($key)) {
            $this->cache->put($key, $item, 10);
        }
        $this->pullListCacheArond($item[$item->getKeyName()]);
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(array $dataSet)
    {
        $item = $this->repository->createItem($dataSet);
        $this->cache->put($this->getKey($item[$item->getKeyName()]), $item, 10);
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $key = $this->getKey($id);
        if ($this->cache->has($key)) {
            $this->cache->pull($key);
        }
        $this->pullListCacheArond($id);
        $this->repository->deleteItem($id);
    }

    /**
     * @param string $id
     */
    protected function pullListCacheArond($id)
    {
        foreach ($this->getPrevItems($id) as $item) {
            $this->cache->pull($this->getKey($item[$item->getKeyName()]) .'.next');
        }
        foreach ($this->getNextItems($id) as $item) {
            $this->cache->pull($this->getKey($item[$item->getKeyName()]) .'.prev');
        }
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getKey($id)
    {
        return "{$this->prefix}.{$id}";
    }

    /**
     * @param int $length
     */
    protected function checkLength($length)
    {
        if (isset($length)) {
            throw new \RuntimeException('cached repository can use only default length!');
        }
    }
}
