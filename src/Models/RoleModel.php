<?php

namespace Shigabutdinoff\Lararoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shigabutdinoff\JsonRelation\Traits\JsonRelationTrait;
use Shigabutdinoff\Lararoles\Casts\JsonUniqueCast;

class RoleModel extends Model
{
    use SoftDeletes, JsonRelationTrait;

    protected $primaryKey = 'user_id';

    protected $table = 'roles';

    protected $fillable = [
        'roles',
    ];

    protected $casts = [
        'roles' => JsonUniqueCast::class,
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
