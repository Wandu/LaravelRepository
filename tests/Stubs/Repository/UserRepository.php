<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\User;
use Wandu\Laravel\Repository\Traits\PaginationRepositoryTrait;

class UserRepository extends Repository implements PaginationRepositoryInterface
{
    use PaginationRepositoryTrait;

    /** @var string */
    protected $model = User::class;
}
