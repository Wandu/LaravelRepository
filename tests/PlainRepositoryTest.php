<?php
namespace Wandu\Laravel\Repository;

use PHPUnit_Framework_TestCase;
use Wandu\Laravel\Repository\Stubs\UserRepository;

class PlainRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepository */
    protected $users;

    public function setUp()
    {
        $this->users = new UserRepository();
    }

    public function testCreate()
    {
        $user = $this->users->createItem(['username' => 'wan2land', 'password' => 'wan2land!']);

        $this->assertEquals([
            'id' => $user['id'],
            'username' => 'wan2land',
            'password' => 'wan2land!'
        ], $this->users->getItem($user['id'])->toArray());
    }
}
