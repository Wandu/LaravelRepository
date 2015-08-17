<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit_Framework_TestCase;
use Wandu\Laravel\Repository\Stubs\Article;
use Wandu\Laravel\Repository\Stubs\ArticleRepository;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class PaginationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepository */
    protected $users;

    /** @var User */
    protected $user;

    public function setUp()
    {
        User::truncate();
        $this->users = new UserRepository();

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

    public function testCreateAndGet()
    {
        $user = $this->users->createItem(['username' => 'newuser', 'password' => 'newuser!!!']);

        $this->assertEquals([
            'id' => $user['id'],
            'username' => 'newuser',
            'password' => 'newuser!!!',
        ], $this->users->getItem($user['id'])->toArray());
    }

    public function testUpdate()
    {
        $this->users->updateItem($this->user['id'], [
            'username' => 'changed'
        ]);
        $this->assertEquals([
            'id' => $this->user['id'],
            'username' => 'changed',
            'password' => 'wan2land!',
        ], $this->users->getItem($this->user['id'])->toArray());
    }

    public function testDelete()
    {
        $this->users->deleteItem($this->user['id']);
        $this->assertNull($this->users->getItem($this->user['id']));
    }

    public function testGetPrevItems()
    {
        $users = $this->users->getItems(0, 10);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(10, count($users));

        $this->assertEquals('dummy100', $users->shift()['username']);
        $this->assertEquals('dummy99', $users->shift()['username']);
        $this->assertEquals('dummy98', $users->shift()['username']);
        $this->assertEquals('dummy97', $users->shift()['username']);
        $this->assertEquals('dummy96', $users->shift()['username']);
        $this->assertEquals('dummy95', $users->shift()['username']);
        $this->assertEquals('dummy94', $users->shift()['username']);
        $this->assertEquals('dummy93', $users->shift()['username']);
        $this->assertEquals('dummy92', $users->shift()['username']);
        $this->assertEquals('dummy91', $users->shift()['username']);

        $users = $this->users->getItems(3, 5);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(5, count($users));

        $this->assertEquals('dummy97', $users->shift()['username']);
        $this->assertEquals('dummy96', $users->shift()['username']);
        $this->assertEquals('dummy95', $users->shift()['username']);
        $this->assertEquals('dummy94', $users->shift()['username']);
        $this->assertEquals('dummy93', $users->shift()['username']);
    }
}
