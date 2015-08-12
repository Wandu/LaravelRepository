<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\Base\DatabaseRepository;

class UserRepository extends DatabaseRepository
{
    /** @var string */
    protected $model = User::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
