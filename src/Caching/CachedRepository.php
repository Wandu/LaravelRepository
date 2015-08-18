<?php
namespace Wandu\Laravel\Repository\Caching;

use Illuminate\Contracts\Cache\Repository as CacheContract;
use Wandu\Laravel\Repository\RepositoryInterface;

class CachedRepository extends ByPassCachingRepository implements RepositoryInterface
{
    /** @var CacheContract */
    protected $cache;

    /** @var string */
    protected $prefix;

    /** @var  */
    protected $minutes = 10;

    /**
     * @param RepositoryInterface $repository
     * @param CacheContract $cache
     * @param string $prefix
     */
    public function __construct(RepositoryInterface $repository, CacheContract $cache, $prefix = 'wandu')
    {
        parent::__construct($repository);
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        $key = $this->getItemKey($id);
        if (!$this->cache->has($key)) {
            $item = $this->repository->getItem($id);
            if (!$item) {
                return null;
            }
            $this->cache->put($key, $item, $this->minutes);
        }
        return $this->cache->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        $key = $this->getItemKey($id);
        $item = $this->repository->updateItem($id, $dataSet);
        if ($this->cache->has($key)) {
            $this->cache->put($key, $item, $this->minutes);
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(array $dataSet)
    {
        $item = $this->repository->createItem($dataSet);
        $this->cache->put($this->getItemKey($item[$item->getKeyName()]), $item, $this->minutes);
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $key = $this->getItemKey($id);
        if ($this->cache->has($key)) {
            $this->cache->pull($key);
        }
        $this->repository->deleteItem($id);
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getItemKey($id)
    {
        return "{$this->prefix}.items.{$id}";
    }
}
