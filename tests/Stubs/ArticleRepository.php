<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\MoreItemRepositoryInterface;
use Wandu\Laravel\Repository\Traits\MoreItemRepositoryTrait;

class ArticleRepository implements MoreItemRepositoryInterface
{
    use MoreItemRepositoryTrait;

    /** @var string */
    protected $model = Article::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
