<?php

namespace Shigabutdinoff\Lararoles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Shigabutdinoff\JsonRelation\JsonRelation;
use Shigabutdinoff\Lararoles\Models\RoleModel;

class Roles extends Controller
{
    protected $user;
    protected RoleModel $role;

    public function __construct()
    {
        $this->role = RoleModel::getModel();
        $this->user = JsonRelation::addToModel(User::class)
            ->hasOneMacro(RoleModel::class)
            ->with($this->role->getTable());
    }

    public function update($id, $roles)
    {
        $ok = $this->role->find($id)->update(['roles' => $roles]);
        return response()->json([
            'ok' => $ok,
            'result' => $this->user->find($id),
            'description' => '',
        ]);
    }

    public function index()
    {
        $id = 2;
        $roles = ['guest'];
        return $this->update($id, $roles);

    }
}
