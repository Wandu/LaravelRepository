<?php
namespace Wandu\Laravel\Repository;

use Wandu\Laravel\Repository\DataMapper\Collection;
use Wandu\Laravel\Repository\DataMapper\DataMapper;
use Wandu\Laravel\Repository\Stubs\DataMapper\Article;
use Wandu\Laravel\Repository\Stubs\Repository\ArticleRepository;

class MoreItemsRepositoryTest extends RepositoryTestCase
{
    /** @var \Wandu\Laravel\Repository\Stubs\Repository\ArticleRepository */
    protected $articles;

    public function setUp()
    {
        parent::setUp();
        $this->articles = new ArticleRepository();
    }

    public function testGetFirstItem()
    {
        $item = $this->articles->getFirstItem();

        $this->assertInstanceOf(DataMapper::class, $item);
        $this->assertInstanceOf(Article::class, $item);
        $this->assertEquals([
            'id' => 200,
            'username' => 'blanda.norval',
            'content' => 'Sequi non quis beatae autem aliquid nobis. ' .
                'Adipisci et atque non adipisci et debitis sapiente. ' .
                'Reprehenderit ut qui excepturi at odio qui vel. ' .
                'Vel tempora sed vel officia harum explicabo tempore.',
            'vote' => 0,
            'created_at' => '2015-08-27 20:42:30',
            'updated_at' => '2015-08-27 20:42:30'
        ], $item->toArray());
    }

    public function testGetNextItems()
    {
        $items = $this->articles->getNextItems(190, 7);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(7, count($items));

        $this->assertInstanceOf(DataMapper::class, $items[0]);
        $this->assertInstanceOf(Article::class, $items[0]);

        $this->assertEquals(189, $items[0]['id']);
        $this->assertEquals(188, $items[1]['id']);
        $this->assertEquals(187, $items[2]['id']);
        $this->assertEquals(186, $items[3]['id']);
        $this->assertEquals(185, $items[4]['id']);
        $this->assertEquals(184, $items[5]['id']);
        $this->assertEquals(183, $items[6]['id']);
    }

    public function testGetPrevItems()
    {
        $items = $this->articles->getPrevItems(190, 5);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals(5, count($items));

        $this->assertEquals(195, $items[0]['id']);
        $this->assertEquals(194, $items[1]['id']);
        $this->assertEquals(193, $items[2]['id']);
        $this->assertEquals(192, $items[3]['id']);
        $this->assertEquals(191, $items[4]['id']);
    }
}
