<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\MoreItemsRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\DataMapper\Article as ArticleMapper;
use Wandu\Laravel\Repository\Stubs\Model\Article as ArticleActiveRecord;
use Wandu\Laravel\Repository\Traits\MoreItemsRepositoryTrait;

class ArticleRepository extends Repository implements MoreItemsRepositoryInterface
{
    use MoreItemsRepositoryTrait;

    /** @var string */
    protected $model = ArticleActiveRecord::class;

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        return new ArticleMapper($class);
    }
}
