<?php
namespace Wandu\Laravel\Repository\Stubs;

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
        'user',
        'vote',
    ];

    /** @var array */
    protected $hidden = ['created_at', 'updated_at'];
}
