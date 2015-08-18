<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit_Framework_TestCase;
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

    public function testFindItems()
    {
        $items = $this->users->findItems(['username' => 'wan2land']);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(1, count($items));
    }

    public function testGetAllItems()
    {
        $items = $this->users->getAllItems();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(101, count($items));
    }

    public function testCreateItem()
    {
        $user = $this->users->createItem(['username' => 'newuser', 'password' => 'newuser!!!']);

        $this->assertEquals([
            'id' => $user['id'],
            'username' => 'newuser',
            'password' => 'newuser!!!',
        ], $this->users->getItem($user['id'])->toArray());

        $this->assertEquals(102, count($this->users->getAllItems()));
    }

    public function testUpdateItem()
    {
        $this->users->updateItem($this->user['id'], [
            'password' => 'changed'
        ]);
        $this->assertEquals([
            'id' => $this->user['id'],
            'username' => 'wan2land',
            'password' => 'changed',
        ], $this->users->getItem($this->user['id'])->toArray());

        $this->assertEquals(101, count($this->users->getAllItems()));
    }

    public function testDeleteItem()
    {
        $this->users->deleteItem($this->user['id']);
        $this->assertNull($this->users->getItem($this->user['id']));

        $this->assertEquals(100, count($this->users->getAllItems()));
    }

    public function testGetItems()
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

    public function testDeleteAndGetItems()
    {
        // for caching~
        foreach (range(0, 20) as $i) {
            $this->users->getItems($i, 10);
        }
        foreach (range(0, 20) as $i) {
            $this->users->getItems($i, 5);
        }

        // remove Item!
        $this->users->deleteItem(95);

        foreach (range(0, 10) as $i) {
            $users = $this->users->getItems($i, 10);

            $this->assertInstanceOf(Collection::class, $users);
            $this->assertEquals(10, count($users));

            $start = 100 - $i;
            foreach (range(0, 10) as $j) {
                if ($i + $j === 6 || $i > 6) { // i 가 6을 넘으면 6이 같음을 체크할수가 없음.
                    continue;
                }
                $this->assertEquals('dummy' . ($start - $j), $users->shift()['username']);
            }
        }
    }

    public function testUpdateAndGetItems()
    {
        // for caching~
        foreach (range(0, 20) as $i) {
            $this->users->getItems($i, 10);
        }
        foreach (range(0, 20) as $i) {
            $this->users->getItems($i, 5);
        }

        // remove Item!
        $this->users->updateItem(95, [
            'password' => 'changed!'
        ]);

        foreach (range(0, 10) as $i) {
            $users = $this->users->getItems($i, 10);

            $this->assertInstanceOf(Collection::class, $users);
            $this->assertEquals(10, count($users));

            if ($i <= 6) {
                $this->assertEquals('changed!', $users[6-$i]['password']);
            }
        }
    }
}
