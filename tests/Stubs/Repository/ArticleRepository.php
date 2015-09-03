<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\DataMapper\DataMapper;
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

    /** @var \Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository */
    protected $hits;

    /**
     * @param \Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository $hits
     */
    public function __construct(ArticleHitRepository $hits)
    {
        $this->hits = $hits;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        $item = new ArticleMapper($class);
        if (!isset($item['count'])) {
            $countOfArticles = $this->hits->getAggregationOfCountByArticle([$item['id']]);
            $item['count'] = (int)(isset($countOfArticles[$item['id']]) ? $countOfArticles[$item['id']] : 0);
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function toMappers(EloquentCollection $collection)
    {
        $ids = $collection->map(function (Model $item) {
            return $item['id'];
        })->toArray();
        $countOfArticles = $this->hits->getAggregationOfCountByArticle($ids);
        return parent::toMappers($collection)->map(function (DataMapper $item) use ($countOfArticles) {
            $item['count'] = (int)(isset($countOfArticles[$item['id']]) ? $countOfArticles[$item['id']] : 0);
            return $item;
        });
    }


}
