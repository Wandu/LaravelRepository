<?php
use Faker\Factory;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Wandu\Laravel\Repository\Stubs\Model\Article;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit;
use Wandu\Laravel\Repository\Stubs\Model\Category;
use Wandu\Laravel\Repository\Stubs\Model\User;

require __DIR__ . '/vendor/autoload.php';

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => './stubs.sqlite',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$capsule->schema()->dropIfExists('article_hits');
$capsule->schema()->dropIfExists('categories');
$capsule->schema()->dropIfExists('articles');
$capsule->schema()->dropIfExists('users');

$capsule->schema()->create('users', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('username', 100)->unique();
    $table->string('password', 100);
    $table->timestamps();
});

$capsule->schema()->create('articles', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('username', 100);
    $table->string('content', 100)->unique();
    $table->integer('vote')->default(0);
    $table->timestamps();
});

$capsule->schema()->create('article_hits', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->bigInteger('article_id')->unsingied();
    $table->bigInteger('user_id')->unsingied();
    $table->timestamps();
});

$capsule->schema()->create('categories', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->bigInteger('article_id')->unsigned();
    $table->string('name', 100);
});

$faker = Factory::create();

for ($i = 0; $i < 100; $i++) {
    User::create([
        'username' => $faker->userName,
        'password' => $faker->password,
    ]);
}

for ($i = 0; $i < 200; $i++) {
    Article::create([
        'username' => User::find(rand(1, 100))['username'],
        'content' => $faker->text
    ]);
}

for ($i = 0; $i < 200; $i++) {
    Category::create([
        'article_id' => rand(1, 200),
        'name' => ['Q&A', 'PHP', 'FreeBoard', 'Javascript', 'Go'][rand(0, 4)]
    ]);
}

for ($i = 0; $i < 400; $i++) {
    ArticleHit::create([
        'article_id' => rand(1, 200),
        'user_id' => rand(1, 100),
    ]);
}
