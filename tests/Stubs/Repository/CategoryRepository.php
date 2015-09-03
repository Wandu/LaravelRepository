<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Illuminate\Database\Eloquent\Model;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\DataMapper\Category as CategoryMapper;
use Wandu\Laravel\Repository\Stubs\Model\Category as CategoryActiveRecord;

class CategoryRepository extends Repository
{
    /** @var string */
    protected $model = CategoryActiveRecord::class;

    /**
     * @param \Illuminate\Database\Eloquent\Model $class
     * @return \Wandu\Laravel\Repository\Stubs\DataMapper\Article
     */
    public function toMapper(Model $class)
    {
        return new CategoryMapper($class);
    }

    /**
     * @param array $articleIds
     * @return \Illuminate\Support\Collection
     */
    public function getAttributesOfNameByArticle(array $articleIds)
    {
        return $this->createQuery()
            ->whereIn('article_id', $articleIds)->get()->groupBy('article_id');
    }
}
