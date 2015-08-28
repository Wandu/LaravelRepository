<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\DataMapper\Article as ArticleMapper;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit as ArticleHitActiveRecord;

class ArticleHitRepository extends Repository
{
    /** @var string */
    protected $model = ArticleHitActiveRecord::class;

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        return new ArticleMapper($class);
    }
}
