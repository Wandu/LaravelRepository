<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit_Framework_TestCase;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class PlainRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepository */
    protected $articles;

    /** @var User */
    protected $user;

    public function setUp()
    {
        Article::truncate();
        $this->articles = new ArticleRepository();

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

    public function testGetAllItems()
    {
        $users = $this->articles->getAllItems();

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(101, count($users));
    }

    public function testCreateAndGet()
    {
        $user = $this->articles->createItem(['content' => 'newuser', 'user' => 'newuser!!!', 'vote' => null]);

        $this->assertEquals([
            'id' => $user['id'],
            'content' => 'newuser',
            'user' => 'newuser!!!',
            'vote' => null
        ], $this->articles->getItem($user['id'])->toArray());
    }

    public function testUpdate()
    {
        $this->articles->updateItem($this->user['id'], [
            'content' => 'changed'
        ]);
        $this->assertEquals([
            'id' => $this->user['id'],
            'content' => 'changed',
            'user' => 'wan2land!',
            'vote' => null
        ], $this->articles->getItem($this->user['id'])->toArray());
    }

    public function testDelete()
    {
        $this->articles->deleteItem($this->user['id']);
        $this->assertNull($this->articles->getItem($this->user['id']));
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
        $users = $this->articles->getNextItems($this->user['id']);

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
        $users = $this->articles->getPrevItems($this->user['id']);

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
}
