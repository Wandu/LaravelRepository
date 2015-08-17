<?php
namespace Wandu\Laravel\Repository\Base;

use Wandu\Laravel\Repository\MoreItemRepositoryInterface;
use Wandu\Laravel\Repository\Traits\MoreItemRepositoryTrait;

abstract class DatabaseRepository implements MoreItemRepositoryInterface
{
    use MoreItemRepositoryTrait;

    /** @var string */
    protected $model;

    /** @var array */
    protected $orderBy = [];
}
