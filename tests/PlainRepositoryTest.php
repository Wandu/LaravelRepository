<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use PHPUnit_Framework_TestCase;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class PlainRepositoryTest extends PHPUnit_Framework_TestCase
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
            'password' => 'newuser!!!'
        ], $this->users->getItem($user['id'])->toArray());
    }

    public function testUnableCreate()
    {
        try {
            $this->users->createItem(['username' => 'wan2land', 'password' => 'wan2land..']);
            $this->fail();
        } catch (QueryException $e) {
            $this->assertEquals(23000, $e->getCode()); // sql exception
        }
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

    public function testGetFirstItem()
    {
        $user = $this->users->getFirstItem();

        $this->assertEquals([
            'id' => $user['id'],
            'username' => 'dummy100',
            'password' => 'dummy100!!',
        ], $user->toArray());
    }

    public function testGetNextItems()
    {
        $users = $this->users->getNextItems($this->user['id']);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(10, count($users));

        $this->assertEquals($this->user->toArray(), $users->shift()->toArray());

        $this->assertEquals('dummy50', $users->shift()['username']);
        $this->assertEquals('dummy49', $users->shift()['username']);
        $this->assertEquals('dummy48', $users->shift()['username']);
        $this->assertEquals('dummy47', $users->shift()['username']);
        $this->assertEquals('dummy46', $users->shift()['username']);
        $this->assertEquals('dummy45', $users->shift()['username']);
        $this->assertEquals('dummy44', $users->shift()['username']);
        $this->assertEquals('dummy43', $users->shift()['username']);
        $this->assertEquals('dummy42', $users->shift()['username']);
    }

    public function testGetPrevItems()
    {
        $users = $this->users->getPrevItems($this->user['id']);

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertEquals(10, count($users));

        $this->assertEquals('dummy59', $users->shift()['username']);
        $this->assertEquals('dummy58', $users->shift()['username']);
        $this->assertEquals('dummy57', $users->shift()['username']);
        $this->assertEquals('dummy56', $users->shift()['username']);
        $this->assertEquals('dummy55', $users->shift()['username']);
        $this->assertEquals('dummy54', $users->shift()['username']);
        $this->assertEquals('dummy53', $users->shift()['username']);
        $this->assertEquals('dummy52', $users->shift()['username']);
        $this->assertEquals('dummy51', $users->shift()['username']);

        $this->assertEquals($this->user->toArray(), $users->shift()->toArray());
    }
}
