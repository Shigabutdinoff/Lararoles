<?php

namespace Shigabutdinoff\Lararoles;

use App\Http\Controllers\Controller;
use Shigabutdinoff\JsonRelation\JsonRelation;
use Shigabutdinoff\Lararoles\Models\RoleModel;

class Roles extends Controller
{

    protected RoleModel $role;

    public function __construct()
    {
        $this->role = RoleModel::getModel();
    }

    public static function index()
    {
        return (JsonRelation::addToModel(\App\Models\User::getModel())
            ->hasOneMacro(Models\RoleModel::class, 'roles_clone')
            ->with('roles_clone')
            ->get()
            );
    }
}
