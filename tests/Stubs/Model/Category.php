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
        'name',
    ];

    /** @var bool */
    public $timestamps = false;
}
