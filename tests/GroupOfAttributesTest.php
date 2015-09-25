<?php
namespace Wandu\Laravel\Repository;

use Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository;
use Wandu\Laravel\Repository\Stubs\Repository\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\Repository\CategoryRepository;
use Wandu\Laravel\Repository\Stubs\Repository\UserRepository;

class GroupOfAttributesTest extends RepositoryTestCase
{
    /** @var \Wandu\Laravel\Repository\Stubs\Repository\ArticleRepository */
    protected $articles;

    /** @var \Wandu\Laravel\Repository\Stubs\Repository\UserRepository */
    protected $users;

    public function setUp()
    {
        parent::setUp();
        $this->articles = new ArticleRepository(new ArticleHitRepository(), new CategoryRepository());
        $this->users = new UserRepository();
    }

    public function testGroupOfAttributes()
    {
//        $articles = $this->articles->getItemsById([5,6,7,9,51,52]);
//        $users = $this->users->getItemsById($articles->pluck('username')->unique()->values()->toArray());
//        print_r($users);
    }
}
