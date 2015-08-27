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
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
