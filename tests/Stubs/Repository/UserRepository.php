<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\DataMapper\User as UserMapper;
use Wandu\Laravel\Repository\Stubs\Model\User as UserActiveRecord;
use Wandu\Laravel\Repository\Traits\PaginationRepositoryTrait;

class UserRepository extends Repository implements PaginationRepositoryInterface
{
    use PaginationRepositoryTrait;

    /** @var string */
    protected $model = UserActiveRecord::class;

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        return new UserMapper($class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createQuery()
    {
        return parent::createQuery()->orderBy('id', 'DESC');
    }
}
