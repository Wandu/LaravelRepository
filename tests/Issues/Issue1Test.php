<?php
namespace Wandu\Laravel\Repository\Issues;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\RepositoryTestCase;
use Illuminate\Database\Capsule\Manager as Capsule;

class Issue1Test extends RepositoryTestCase
{
    /** @var \Wandu\Laravel\Repository\Issues\Issue1Repository */
    protected $models;

    public function setUp()
    {
        parent::setUp();
        Capsule::schema()->create('issue1_model', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('options');
        });
        $this->models = new Issue1Repository();
    }

    public function testCreate()
    {
        $model = $this->models->createItem([
            'options' => [
                'test1' => 'test11',
                'test2' => 'test22',
            ],
        ]);

        $this->assertEquals([
            'id' => 1,
            'options' => [
                'test1' => 'test11',
                'test2' => 'test22',
            ],
        ], $model->toArray());

        $this->assertEquals([
            'id' => 1,
            'options' => [
                'test1' => 'test11',
                'test2' => 'test22',
            ],
        ], $this->models->getItem(1)->toArray());
    }

    public function testUpdate()
    {
        $this->models->createItem([
            'options' => [
                'test1' => 'test11',
                'test2' => 'test22',
            ],
        ]);

        $this->assertEquals([
            'id' => 1,
            'options' => [
                'test1' => 'test1111',
                'test2' => 'test2222',
            ],
        ], $this->models->updateItem(1, [
            'options' => [
                'test1' => 'test1111',
                'test2' => 'test2222',
            ],
        ])->toArray());

        $this->assertEquals([
            'id' => 1,
            'options' => [
                'test1' => 'test1111',
                'test2' => 'test2222',
            ],
        ], $this->models->getItem(1)->toArray());
    }
}

class Issue1Model extends Model
{
    /** @var string */
    protected $table = "issue1_model";

    /** @var array */
    protected $fillable = [
        'options',
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $casts = [
        'options' => 'array',
    ];
}

class Issue1Repository extends Repository
{
    /** @var string */
    public $model = Issue1Model::class;
}
