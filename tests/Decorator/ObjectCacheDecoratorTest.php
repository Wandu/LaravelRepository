<?php
namespace Wandu\Laravel\Repository\Decorator;

use Wandu\Laravel\Repository\PlainRepositoryTest;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class ObjectCacheDecoratorTest extends PlainRepositoryTest
{
    /** @var ObjectCacheDecorator */
    protected $articles;

    /** @var User */
    protected $user;

    public function setUp()
    {
        Article::truncate();
        $this->articles = new ObjectCacheDecorator(new ArticleRepository(), $GLOBALS['cache']);

        // salt
        for ($i = 1; $i <= 50; $i++) {
            $this->articles->createItem(['content' => "dummy{$i}", 'user' => "dummy{$i}!!"]);
        }
        // specific user
        $this->user = $this->articles->createItem(['content' => 'wan2land', 'user' => 'wan2land!']);
        for ($i = 51; $i <= 100; $i++) {
            $this->articles->createItem(['content' => "dummy{$i}", 'user' => "dummy{$i}!!"]);
        }
    }

    public function tearDown()
    {
        // all cache flush
        $GLOBALS['fileSystem']->deleteDirectory(__DIR__ .'/../../cache');
    }

    public function testGetNextItems()
    {
        // twice because of cache!
        parent::testGetNextItems();
        parent::testGetNextItems();
    }

    public function testGetPrevItems()
    {
        // twice because of cache!
        parent::testGetPrevItems();
        parent::testGetPrevItems();
    }

    public function testCacheAfterDelete()
    {
        // before delete
        $nextItems = $this->articles->getNextItems(80);
        $this->assertEquals([
            'id' => 75,
            'content' => 'dummy74',
            'user' => 'dummy74!!',
            'vote' => null,
        ], $nextItems[4]->toArray());

        $prevItems = $this->articles->getPrevItems(70);
        $this->assertEquals([
            'id' => 75,
            'content' => 'dummy74',
            'user' => 'dummy74!!',
            'vote' => null,
        ], $prevItems[5]->toArray());

        $this->articles->deleteItem(75);

        // after delete
        $nextItems = $this->articles->getNextItems(80);
        $this->assertEquals([
            'id' => 74,
            'content' => 'dummy73',
            'user' => 'dummy73!!',
            'vote' => null,
        ], $nextItems[4]->toArray());

        $prevItems = $this->articles->getPrevItems(70);
        $this->assertEquals([
            'id' => 76,
            'content' => 'dummy75',
            'user' => 'dummy75!!',
            'vote' => null,
        ], $prevItems[5]->toArray());
    }

    public function testCacheAfterUpdate()
    {
        // before delete
        $nextItems = $this->articles->getNextItems(80);
        $this->assertEquals([
            'id' => 75,
            'content' => 'dummy74',
            'user' => 'dummy74!!',
            'vote' => null,
        ], $nextItems[4]->toArray());

        $prevItems = $this->articles->getPrevItems(70);
        $this->assertEquals([
            'id' => 75,
            'content' => 'dummy74',
            'user' => 'dummy74!!',
            'vote' => null,
        ], $prevItems[5]->toArray());

        $this->articles->updateItem(75, [
            'content' => 'updated!!'
        ]);

        // after delete
        $nextItems = $this->articles->getNextItems(80);
        $this->assertEquals([
            'id' => 75,
            'content' => 'updated!!',
            'user' => 'dummy74!!',
            'vote' => null
        ], $nextItems[4]->toArray());

        $prevItems = $this->articles->getPrevItems(70);
        $this->assertEquals([
            'id' => 75,
            'content' => 'updated!!',
            'user' => 'dummy74!!',
            'vote' => null
        ], $prevItems[5]->toArray());
    }
}
