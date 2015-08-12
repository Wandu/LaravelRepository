<?php
namespace Wandu\Laravel\Repository\Stubs;

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
    protected $hidden = ['created_at', 'updated_at'];
}
