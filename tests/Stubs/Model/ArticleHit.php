<?php
namespace Wandu\Laravel\Repository\Stubs\Model;

use Illuminate\Database\Eloquent\Model;

class ArticleHit extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $table = 'article_hits';

    /** @var array */
    protected $fillable = [
        'article_id',
        'user_id',
    ];

    /** @var array */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
