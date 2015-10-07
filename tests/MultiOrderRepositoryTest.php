<?php
namespace Wandu\Laravel\Repository;

use Mockery;
use Wandu\Laravel\Repository\Stubs\Repository\CommentRepository;

class MultiOrderRepositoryTest extends RepositoryTestCase
{
    /** @var \Wandu\Laravel\Repository\Stubs\Repository\CommentRepository */
    protected $comments;

    public function setUp()
    {
        parent::setUp();
        $this->comments = new CommentRepository();
    }

    public function testGetItems()
    {
        $this->assertEquals(
            [2,4,5,7,3,6,1],
            $this->comments->getAllItems()->pluck('id')->toArray()
        );
    }

    public function testGetFirstItem()
    {
        $this->assertEquals(
            2,
            $this->comments->getFirstItem()['id']
        );
    }

    public function testGetNextItems()
    {
        $this->assertEquals(
            [4,5,7,3,6,1],
            $this->comments->getNextItems(2)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [5,7,3,6,1],
            $this->comments->getNextItems(4)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [7,3,6,1],
            $this->comments->getNextItems(5)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [3,6,1],
            $this->comments->getNextItems(7)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [6,1],
            $this->comments->getNextItems(3)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [1],
            $this->comments->getNextItems(6)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [],
            $this->comments->getNextItems(1)->pluck('id')->toArray()
        );
    }

    public function testGetPrevItems()
    {
        $this->assertEquals(
            [],
            $this->comments->getPrevItems(2)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2],
            $this->comments->getPrevItems(4)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2,4],
            $this->comments->getPrevItems(5)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2,4,5],
            $this->comments->getPrevItems(7)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2,4,5,7],
            $this->comments->getPrevItems(3)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2,4,5,7,3],
            $this->comments->getPrevItems(6)->pluck('id')->toArray()
        );
        $this->assertEquals(
            [2,4,5,7,3,6],
            $this->comments->getPrevItems(1)->pluck('id')->toArray()
        );
    }
}
