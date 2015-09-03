<?php
namespace Wandu\Laravel\Repository\Stubs\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $table = 'articles';

    /** @var array */
    protected $fillable = [
        'content',
        'username',
        'vote',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'vote' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'article_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hits()
    {
        return $this->hasMany(ArticleHit::class, 'article_id', 'id');
    }
}
