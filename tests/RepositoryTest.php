<?php
namespace Wandu\Laravel\Repository;

use Mockery;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit;
use Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository;

class RepositoryTest extends RepositoryTestCase
{
    /** @var \Wandu\Laravel\Repository\Stubs\Repository\ArticleHitRepository */
    protected $hits;

    public function setUp()
    {
        parent::setUp();

        $this->hits = new ArticleHitRepository();
    }

    public function testGetItemWithCaching()
    {
        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('fire')->with('illuminate.query', Mockery::any())->once();

        $this->connection->setEventDispatcher($dispatcher);

        $this->hits->getItem(3);
        $this->hits->getItem(3);
        $this->hits->getItem(3);
        $this->hits->getItem(3);
    }

    public function testFindItems()
    {
        $items = $this->hits->findItems(['article_id' => 30]);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertSame([
            [
                'id' => 332,
                'article_id' => 30,
                'user_id' => 97,
            ],
            [
                'id' => 357,
                'article_id' => 30,
                'user_id' => 42,
            ]
        ], $items->toArray());

        $this->assertInstanceOf(Model::class, $items[0]);
        $this->assertInstanceOf(ArticleHit::class, $items[0]);
        $this->assertSame([
            'id' => 332,
            'article_id' => 30,
            'user_id' => 97,
        ], $items[0]->toArray());
        $this->assertInstanceOf(Model::class, $items[1]);
        $this->assertInstanceOf(ArticleHit::class, $items[1]);
        $this->assertSame([
            'id' => 357,
            'article_id' => 30,
            'user_id' => 42,
        ], $items[1]->toArray());
    }

    public function testGetAllItems()
    {
        $items = $this->hits->getAllItems();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(400, count($items));

        $this->assertInstanceOf(Model::class, $items[331]);
        $this->assertInstanceOf(ArticleHit::class, $items[331]);
        $this->assertSame([
            'id' => 332,
            'article_id' => 30,
            'user_id' => 97,
        ], $items[331]->toArray());
    }

    public function testGetItem()
    {
        $item = $this->hits->getItem(3);
        $this->assertInstanceOf(Model::class, $item);
        $this->assertInstanceOf(ArticleHit::class, $item);

        $this->assertEquals([
            'id' => 3,
            'article_id' => 164,
            'user_id' => 75,
        ], $item->toArray());

        $this->assertNull($this->hits->getItem(-1));
    }

    public function testGetItemsById()
    {
        $items = $this->hits->getItemsById([3, 5, 50]);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(3, count($items));

        $this->assertEquals([
            'id' => 3,
            'article_id' => 164,
            'user_id' => 75,
        ], $items[0]->toArray());
        $this->assertEquals([
            'id' => 5,
            'article_id' => 62,
            'user_id' => 11,
        ], $items[1]->toArray());
        $this->assertEquals([
            'id' => 50,
            'article_id' => 75,
            'user_id' => 78,
        ], $items[2]->toArray());
    }

    public function testCreateItem()
    {
        $this->assertEquals(0, $this->hits->findItems([
            'article_id' => 100,
            'user_id' => 50
        ])->count());

        $item = $this->hits->createItem([
            'article_id' => 100,
            'user_id' => 50,
        ]);

        $this->assertInstanceOf(Model::class, $item);
        $this->assertInstanceOf(ArticleHit::class, $item);
        $this->assertEquals([
            'id' => 401,
            'article_id' => 100,
            'user_id' => 50,
        ], $item->toArray());

        $this->assertEquals(1, $this->hits->findItems([
            'article_id' => 100,
            'user_id' => 50
        ])->count());
    }

    public function testUpdateItem()
    {
        // check 3
        $this->assertEquals([
            'id' => 3,
            'article_id' => 164,
            'user_id' => 75,
        ], $this->hits->getItem(3)->toArray());

        $item = $this->hits->updateItem(3, [
            'article_id' => 163,
            'user_id' => 80
        ]);
        $this->assertInstanceOf(Model::class, $item);
        $this->assertInstanceOf(ArticleHit::class, $item);
        $this->assertEquals([
            'id' => 3,
            'article_id' => 163,
            'user_id' => 80
        ], $item->toArray());

        $this->assertEquals([
            'id' => 3,
            'article_id' => 163,
            'user_id' => 80
        ], $this->hits->getItem(3)->toArray());
    }

    public function testDeleteItem()
    {
        $this->assertEquals([
            'id' => 3,
            'article_id' => 164,
            'user_id' => 75,
        ], $this->hits->getItem(3)->toArray());

        $this->hits->deleteItem(3);

        $this->assertNull($this->hits->getItem(3));
    }

    public function testCountAll()
    {
        $this->assertSame(400, $this->hits->countAll());
    }
}
