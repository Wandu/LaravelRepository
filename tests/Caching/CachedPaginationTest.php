<?php
namespace Wandu\Laravel\Repository\Caching;

use Wandu\Laravel\Repository\PaginationRepositoryTest;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserCachedRepository;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class CachedPaginationTest extends PaginationRepositoryTest
{
    /** @var ObjectCacheDecorator */
    protected $articles;

    /** @var User */
    protected $user;

    public function setUp()
    {
        User::truncate();
        $this->users = new UserCachedRepository(new UserRepository(), $GLOBALS['cache']);

        // salt
        for ($i = 1; $i <= 50; $i++) {
            $this->users->createItem(['username' => "dummy{$i}", 'password' => "dummy{$i}!!"]);
        }
        // specific user
        $this->user = $this->users->createItem(['username' => 'wan2land', 'password' => 'wan2land!']);
        for ($i = 51; $i <= 100; $i++) {
            $this->users->createItem(['username' => "dummy{$i}", 'password' => "dummy{$i}!!"]);
        }
    }

    public function tearDown()
    {
        // all cache flush
        $GLOBALS['fileSystem']->deleteDirectory(__DIR__ .'/../../cache');
    }
}
