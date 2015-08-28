<?php
namespace Wandu\Laravel\Repository\DataMapper;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mockery;
use PHPUnit_Framework_TestCase;

class DataMapperTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testToArray()
    {
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('toArray')->once()->andReturn([
            'id' => 1,
            'username' => 'wan2land',
            'password' => '11112222',
        ]);

        $mapper = new DataMapper($model);
        $this->assertSame([
            'id' => 1,
            'username' => 'wan2land',
            'password' => '11112222',
        ], $mapper->toArray());
    }

    public function testArrayAccess()
    {
        $model = Mockery::mock(Model::class);
        $model->shouldReceive('toArray')->once()->andReturn([
            'id' => 1,
            'username' => 'wan2land',
            'password' => '11112222',
        ]);

        $mapper = new DataMapper($model);

        $this->assertEquals('wan2land', $mapper['username']);

        $mapper['username'] = 'wan2land2';

        $this->assertEquals('wan2land2', $mapper['username']);

        try {
            $mapper[] = 'blabla';
            $this->fail();
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('offset must be a string.', $e->getMessage());
        }

        $this->assertTrue(isset($mapper['password']));

        unset($mapper['password']);

        $this->assertnull($mapper['password']);
        $this->assertFalse(isset($mapper['password']));
    }
}
