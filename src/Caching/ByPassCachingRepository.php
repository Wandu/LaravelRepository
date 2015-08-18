<?php
namespace Wandu\Laravel\Repository\Caching;

use Wandu\Laravel\Repository\RepositoryInterface;

class ByPassCachingRepository implements RepositoryInterface
{
    /** @var RepositoryInterface */
    protected $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        return $this->repository->getAllItems();
    }

    /**
     * {@inheritdoc}
     */
    public function findItems(array $where)
    {
        return $this->repository->findItems($where);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id)
    {
        return $this->repository->getItem($id);
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($id, array $dataSet)
    {
        return $this->repository->updateItem($id, $dataSet);
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(array $dataSet)
    {
        return $this->repository->createItem($dataSet);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        $this->repository->deleteItem($id);
    }
}
