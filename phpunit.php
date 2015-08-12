<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

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
    $table->string('username', 100);
    $table->string('password', 100);
    $table->timestamps();
});
