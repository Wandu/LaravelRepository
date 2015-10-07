<?php
namespace Wandu\Laravel\Repository\Stubs\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $table = 'comments';

    /** @var array */
    protected $fillable = [
        'content',
        'vote',
        'ancestor_vote',
        'order',
        'ancestor_order',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'vote' => 'integer',
        'ancestor_vote' => 'integer',
    ];
}
