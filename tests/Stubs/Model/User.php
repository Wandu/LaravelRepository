<?php
namespace Wandu\Laravel\Repository\Stubs\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $table = 'users';

    /** @var array */
    protected $fillable = [
        'username',
        'password',
    ];

    /** @var array */
    protected $hidden = ['password', 'updated_at'];

    /** @var array */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'username', 'username');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hits()
    {
        return $this->hasMany(ArticleHit::class, 'article_id', 'id');
    }
}
