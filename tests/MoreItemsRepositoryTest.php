<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit_Framework_TestCase;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;

class MoreItemsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var ArticleRepository */
    protected $articles;

    /** @var Article */
    protected $article;

    public function setUp()
    {
        Article::truncate();
        $this->articles = new ArticleRepository();

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

    public function testFindItems()
    {
        $items = $this->articles->findItems(['content' => 'wan2land']);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(1, count($items));
    }

    public function testGetAllItems()
    {
        $users = $this->articles->getAllItems();

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(101, count($users));
    }

    public function testGetItemsById()
    {
        $items = $this->articles->getItemsById([3,5,50]);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(3, count($items));

        $this->assertEquals('dummy3', $items->shift()['content']);
        $this->assertEquals('dummy5', $items->shift()['content']);
        $this->assertEquals('dummy50', $items->shift()['content']);
    }


    public function testCreateItem()
    {
        $user = $this->articles->createItem(['content' => 'newuser', 'user' => 'newuser!!!', 'vote' => null]);

        $this->assertEquals([
            'id' => $user['id'],
            'content' => 'newuser',
            'user' => 'newuser!!!',
            'vote' => null
        ], $this->articles->getItem($user['id'])->toArray());
    }

    public function testUpdateItem()
    {
        $this->articles->updateItem($this->article['id'], [
            'content' => 'changed'
        ]);
        $this->assertEquals([
            'id' => $this->article['id'],
            'content' => 'changed',
            'user' => 'wan2land!',
            'vote' => null
        ], $this->articles->getItem($this->article['id'])->toArray());
    }

    public function testDeleteItem()
    {
        $this->articles->deleteItem($this->article['id']);
        $this->assertNull($this->articles->getItem($this->article['id']));
    }

    public function testGetFirstItem()
    {
        $user = $this->articles->getFirstItem();

        $this->assertEquals([
            'id' => $user['id'],
            'content' => 'dummy100',
            'user' => 'dummy100!!',
            'vote' => null
        ], $user->toArray());
    }

    public function testGetNextItems()
    {
        $users = $this->articles->getNextItems($this->article['id']);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(10, count($users));

        //$this->assertEquals($this->user->toArray(), $users->shift()->toArray());

        $this->assertEquals('dummy50', $users->shift()['content']);
        $this->assertEquals('dummy49', $users->shift()['content']);
        $this->assertEquals('dummy48', $users->shift()['content']);
        $this->assertEquals('dummy47', $users->shift()['content']);
        $this->assertEquals('dummy46', $users->shift()['content']);
        $this->assertEquals('dummy45', $users->shift()['content']);
        $this->assertEquals('dummy44', $users->shift()['content']);
        $this->assertEquals('dummy43', $users->shift()['content']);
        $this->assertEquals('dummy42', $users->shift()['content']);
        $this->assertEquals('dummy41', $users->shift()['content']);
    }

    public function testGetPrevItems()
    {
        $users = $this->articles->getPrevItems($this->article['id']);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(10, count($users));

        $this->assertEquals('dummy60', $users->shift()['content']);
        $this->assertEquals('dummy59', $users->shift()['content']);
        $this->assertEquals('dummy58', $users->shift()['content']);
        $this->assertEquals('dummy57', $users->shift()['content']);
        $this->assertEquals('dummy56', $users->shift()['content']);
        $this->assertEquals('dummy55', $users->shift()['content']);
        $this->assertEquals('dummy54', $users->shift()['content']);
        $this->assertEquals('dummy53', $users->shift()['content']);
        $this->assertEquals('dummy52', $users->shift()['content']);
        $this->assertEquals('dummy51', $users->shift()['content']);
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
