<?php
namespace Wandu\Laravel\Repository\Decorator;

use Wandu\Laravel\Repository\PlainRepositoryTest;
use Wandu\Laravel\Repository\Stubs\User;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class ObjectCacheDecoratorTest extends PlainRepositoryTest
{
    /** @var ObjectCacheDecorator */
    protected $users;

    /** @var User */
    protected $user;

    public function setUp()
    {
        User::truncate();
        $this->users = new ObjectCacheDecorator(new UserRepository(), $GLOBALS['cache']);

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
        $nextItems = $this->users->getNextItems(80);
        $this->assertEquals($this->users->getItem(75)->toArray(), $nextItems[5]->toArray());

        $prevItems = $this->users->getPrevItems(70);
        $this->assertEquals($this->users->getItem(75)->toArray(), $prevItems[4]->toArray());

        $this->users->deleteItem(75);

        // after delete
        $nextItems = $this->users->getNextItems(80);
        $this->assertEquals($this->users->getItem(74)->toArray(), $nextItems[5]->toArray());

        $prevItems = $this->users->getPrevItems(70);
        $this->assertEquals($this->users->getItem(76)->toArray(), $prevItems[4]->toArray());
    }

    public function testCacheAfterUpdate()
    {
        // before delete
        $nextItems = $this->users->getNextItems(80);
        $this->assertEquals($this->users->getItem(75)->toArray(), $nextItems[5]->toArray());

        $prevItems = $this->users->getPrevItems(70);
        $this->assertEquals($this->users->getItem(75)->toArray(), $prevItems[4]->toArray());

        $this->users->updateItem(75, [
            'username' => 'updated!!'
        ]);

        // after delete
        $nextItems = $this->users->getNextItems(80);
        $this->assertEquals([
            'id' => 75,
            'username' => 'updated!!',
            'password' => 'dummy74!!'
        ], $nextItems[5]->toArray());

        $prevItems = $this->users->getPrevItems(70);
        $this->assertEquals([
            'id' => 75,
            'username' => 'updated!!',
            'password' => 'dummy74!!'
        ], $prevItems[4]->toArray());
    }
}
