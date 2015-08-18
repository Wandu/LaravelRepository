<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\MoreItemRepositoryInterface;
use Wandu\Laravel\Repository\Traits\MoreItemsRepositoryTrait;

class ArticleRepository implements MoreItemRepositoryInterface
{
    use MoreItemsRepositoryTrait;

    /** @var string */
    protected $model = Article::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
