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
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'username', 'username');
    }
}
