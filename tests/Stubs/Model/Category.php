<?php
namespace Wandu\Laravel\Repository\Stubs\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $table = 'categories';

    /** @var array */
    protected $fillable = [
        'article_id',
        'name',
    ];

    protected $hidden = [
        'id', 'article_id'
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer'
    ];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
}
