<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Traits\PaginationRepositoryTrait;

class UserRepository implements PaginationRepositoryInterface
{
    use PaginationRepositoryTrait;

    /** @var string */
    protected $model = User::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
