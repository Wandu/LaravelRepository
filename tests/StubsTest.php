<?php
namespace Wandu\Laravel\Repository;

use Illuminate\Database\Eloquent\Collection;
use Wandu\Laravel\Repository\Stubs\Model\Article;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit;
use Wandu\Laravel\Repository\Stubs\Model\Category;
use Wandu\Laravel\Repository\Stubs\Model\User;

class StubsTestCase extends RepositoryTestCase
{
    public function testUser()
    {
        $user = User::find(99);

        $this->assertSame([
            'id' => 99,
            'username' => 'nmayert',
            'created_at' => '2015-08-27 20:42:29',
        ], $user->toArray());

        $this->assertSame([
            'id' => '99',
            'username' => 'nmayert',
            'password' => 'wYyZ*3:0kQ',
            'created_at' => '2015-08-27 20:42:29',
            'updated_at' => '2015-08-27 20:42:29',
        ], $user->getAttributes());

        $this->assertInstanceOf(Collection::class, $user->articles);
        $this->assertInstanceOf(Article::class, $user->articles[0]);
        $this->assertSame(5, $user->articles->count());

        $this->assertInstanceOf(Collection::class, $user->hits);
        $this->assertInstanceOf(ArticleHit::class, $user->hits[0]);
        $this->assertSame(1, $user->hits->count());
    }

    public function testArticle()
    {
        $article = Article::find(82);

        $this->assertSame([
            'id' => 82,
            'username' => 'unique06',
            'content' => 'Explicabo deserunt officia aspernatur. Ut ut sequi non. ' .
                'Natus perferendis maiores non numquam. Qui pariatur repellendus pariatur laboriosam.',
            'vote' => 0,
            'created_at' => '2015-08-27 20:42:29',
            'updated_at' => '2015-08-27 20:42:29',
        ], $article->toArray());

        $this->assertInstanceOf(User::class, $article->user);
        $this->assertSame([
            'id' => 37,
            'username' => 'unique06',
            'created_at' => '2015-08-27 20:42:28',
        ], $article->user->toArray());

        $this->assertInstanceOf(Collection::class, $article->categories);
        $this->assertInstanceOf(Category::class, $article->categories[0]);
        $this->assertSame(2, $article->categories->count());

        $this->assertInstanceOf(Collection::class, $article->hits);
        $this->assertInstanceOf(ArticleHit::class, $article->hits[0]);
        $this->assertSame(1, $article->hits->count());
    }

    public function testAticleHit()
    {
        $hit = ArticleHit::find(30);

        $this->assertSame([
            'id' => 30,
            'article_id' => 143,
            'user_id' => 20
        ], $hit->toArray());

        $this->assertInstanceOf(Article::class, $hit->article);
        $this->assertSame([
            'id' => 143,
            'username' => 'rosina.conn',
            'content' => 'Perspiciatis molestiae distinctio consequuntur aut rerum. ' .
                'Sunt culpa assumenda sapiente quia.',
            'vote' => 0,
            'created_at' => '2015-08-27 20:42:29',
            'updated_at' => '2015-08-27 20:42:29',
        ], $hit->article->toArray());

        $this->assertInstanceOf(User::class, $hit->user);
        $this->assertSame([
            'id' => 20,
            'username' => 'graham.otto',
            'created_at' => '2015-08-27 20:42:28',
        ], $hit->user->toArray());
    }

    public function testCategory()
    {
        $category = Category::find(27);
        $this->assertSame([
            'name' => 'Javascript',
        ], $category->toArray());

        $this->assertSame([
            'id' => '27',
            'article_id' => '34',
            'name' => 'Javascript',
        ], $category->getAttributes());

        $this->assertInstanceOf(Article::class, $category->article);
        $this->assertSame([
            'id' => 34,
            'username' => 'candido.walker',
            'content' => 'Neque alias officiis sint soluta cupiditate nisi. ' .
                'Ut aut amet ipsa ducimus repellat magni. Doloremque omnis possimus cupiditate nostrum autem ut.',
            'vote' => 0,
            'created_at' => '2015-08-27 20:42:29',
            'updated_at' => '2015-08-27 20:42:29',
        ], $category->article->toArray());
    }
}
