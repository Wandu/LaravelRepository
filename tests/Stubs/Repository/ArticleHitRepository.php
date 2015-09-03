<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\DataMapper\ArticleHit as ArticleHitMapper;
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
        return new ArticleHitMapper($class);
    }

    /**
     * @param array $articleIds
     * @return \Illuminate\Support\Collection
     */
    public function getAggregationOfCountByArticle(array $articleIds)
    {
        return $this->createQuery()
            ->select('article_id', new Expression('count(*) as `total`'))
            ->whereIn('article_id', $articleIds)
            ->groupBy('article_id')->lists('total', 'article_id');
    }
}
