<?php
namespace Wandu\Laravel\Repository\Caching;

use Wandu\Laravel\Repository\MoreItemsRepositoryTest;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleCachedRepository;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\ArticleRepositoryInterface;

class CachedMoreItemsTest extends MoreItemsRepositoryTest
{
    /** @var ArticleRepositoryInterface */
    protected $articles;

    /** @var Article */
    protected $article;

    public function setUp()
    {
        Article::truncate();
        $this->articles = new ArticleCachedRepository(new ArticleRepository(), $GLOBALS['cache']);

        // salt
        for ($i = 1; $i <= 50; $i++) {
            $this->articles->createItem(['content' => "dummy{$i}", 'user' => "dummy{$i}!!"]);
        }
        // specific user
        $this->article = $this->articles->createItem(['content' => 'wan2land', 'user' => 'wan2land!']);
        for ($i = 51; $i <= 100; $i++) {
            $this->articles->createItem(['content' => "dummy{$i}", 'user' => "dummy{$i}!!"]);
        }
    }

    public function tearDown()
    {
        // all cache flush
        $GLOBALS['fileSystem']->deleteDirectory(__DIR__ .'/../../cache');
    }
}
