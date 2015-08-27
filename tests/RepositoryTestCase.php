<?php
namespace Wandu\Laravel\Repository;

use PHPUnit_Framework_TestCase;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Filesystem\Filesystem;

class RepositoryTestCase extends PHPUnit_Framework_TestCase
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $fileSystem;

    /** @var \Illuminate\Cache\Repository */
    protected $cache;

    public function setUp()
    {
        $this->fileSystem = new Filesystem();
        $this->cache = new Repository(new FileStore($this->fileSystem, './cache'));

        $this->fileSystem->copy('./stubs.sqlite', './stubs-real.sqlite');

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'sqlite',
            'database'  => './stubs-real.sqlite',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public function tearDown()
    {
        $this->fileSystem->delete('./stubs-real.sqlite');
    }
}
