<?php
namespace Wandu\Laravel\Repository;

use Wandu\Laravel\Repository\DataMapper\Collection;
use Wandu\Laravel\Repository\Stubs\DataMapper\User;
use Wandu\Laravel\Repository\Stubs\Repository\UserRepository;

class PaginationRepositoryTest extends RepositoryTestcase
{
    /** @var \Wandu\Laravel\Repository\Stubs\Repository\UserRepository */
    protected $users;

    public function setUp()
    {
        parent::setUp();
        $this->users = new UserRepository();
    }

    public function testGetItems()
    {
        $users1 = $this->users->getItems(0, 10);
        $this->assertInstanceOf(Collection::class, $users1);
        $this->assertSame(10, $users1->count());

        $this->assertInstanceOf(User::class, $users1[0]);

        $this->assertSame(100, $users1[0]['id']);
        $this->assertSame(91, $users1[9]['id']);
    }
}
