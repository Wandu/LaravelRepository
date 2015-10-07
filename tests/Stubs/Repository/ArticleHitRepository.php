<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Query\Expression;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit;

class ArticleHitRepository extends Repository
{
    /** @var string */
    protected $model = ArticleHit::class;

    /** @var array */
    protected $orders = [
        'id' => true,
    ];

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
