<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\RepositoryInterface;
use Wandu\Laravel\Repository\Traits\WithDatabaseTrait;

class UserRepository implements RepositoryInterface
{
    use WithDatabaseTrait;

    /** @var string */
    protected $model = User::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
