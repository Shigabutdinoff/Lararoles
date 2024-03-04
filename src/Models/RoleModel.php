<?php

namespace Shigabutdinoff\Lararoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shigabutdinoff\JsonRelation\Traits\JsonRelationTrait;

class RoleModel extends Model
{
    use SoftDeletes, JsonRelationTrait;

    protected $primaryKey = 'user_id';

    protected $table = 'roles';

    protected $fillable = [
        'roles',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
