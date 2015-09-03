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

    /** @var \Wandu\Laravel\Repository\Stubs\Repository\CategoryRepository */
    protected $categories;

    /**
     * @param \Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository $hits
     * @param \Wandu\Laravel\Repository\Stubs\Repository\CategoryRepository $categories
     */
    public function __construct(ArticleHitRepository $hits, CategoryRepository $categories)
    {
        $this->hits = $hits;
        $this->categories = $categories;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        $item = new ArticleMapper($class);
        if (!isset($item['hits'])) {
            $countOfArticles = $this->hits->getAggregationOfCountByArticle([$item['id']]);
            $item['hits'] = (int)(isset($countOfArticles[$item['id']]) ? $countOfArticles[$item['id']] : 0);
        }
        if (!isset($item['categories'])) {
            $categoriesOfArticle = $this->categories->getAttributesOfNameByArticle([$item['id']]);
            $item['categories'] = isset($categoriesOfArticle[$item['id']]) ?
                $categoriesOfArticle[$item['id']]->pluck('name')->toArray() :
                [];
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function toMappers(EloquentCollection $collection)
    {
        $ids = $collection->pluck('id')->toArray();
        $hitsOfArticle = $this->hits->getAggregationOfCountByArticle($ids);
        $categoriesOfArticle = $this->categories->getAttributesOfNameByArticle($ids);
        return parent::toMappers($collection->map(function (Model $item) use ($hitsOfArticle, $categoriesOfArticle) {
            $item['hits'] = (int)(isset($hitsOfArticle[$item['id']]) ? $hitsOfArticle[$item['id']] : 0);
            $item['categories'] = isset($categoriesOfArticle[$item['id']]) ?
                $categoriesOfArticle[$item['id']]->pluck('name')->toArray() :
                [];
            return $item;
        }));
    }
}
