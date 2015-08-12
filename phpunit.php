<?php
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;

require __DIR__ . '/vendor/autoload.php';

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => ':memory:',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();


$capsule->schema()->create('users', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('username', 100)->unique();
    $table->string('password', 100);
    $table->timestamps();
});

$fileSystem = new Filesystem();
$cache = new Repository(new FileStore($fileSystem, __DIR__ .'/cache'));
