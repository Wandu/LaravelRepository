<?php
namespace Wandu\Laravel\Repository\Stubs;

use Wandu\Laravel\Repository\Traits\MoreItemsRepositoryTrait;

class ArticleRepository implements ArticleRepositoryInterface
{
    use MoreItemsRepositoryTrait;

    /** @var string */
    protected $model = Article::class;

    /** @var array */
    protected $orderBy = ['id' => false]; // ORDER BY `id` DESC
}
