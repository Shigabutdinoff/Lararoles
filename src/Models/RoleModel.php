<?php

namespace Shigabutdinoff\Lararoles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleModel extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'roles',
    ];

    protected $casts = [
        'roles' => 'array',
    ];
}
