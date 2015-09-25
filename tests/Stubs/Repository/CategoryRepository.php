<?php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\Category;

class CategoryRepository extends Repository
{
    /** @var string */
    protected $model = Category::class;
}
