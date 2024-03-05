<?php

namespace Shigabutdinoff\Lararoles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Shigabutdinoff\JsonRelation\JsonRelation;
use Shigabutdinoff\Lararoles\Models\RoleModel;

class Roles extends Controller
{
    public string $table;

    protected $user;

    public function __construct()
    {
        $this->table = RoleModel::getModel()->getTable();
        $this->user = JsonRelation::addToModel(User::class)
            ->hasOneMacro(RoleModel::class)
            ->with($this->table);
    }

    /**
     * Get the user Model use JsonRelation.
     * @return mixed
     */
    public function user()
    {
        return $this->user->clone();
    }

    public function update($id, $roles)
    {
        $result = $this->user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation($this->table);
            $updated = false;
            if (! is_null($relation)) {
                $updated = (bool) $relation->update(['roles' => ['guest', ... (array)$roles]]);
            }
            return [
                'id' => $model->id,
                'updated' => $updated
            ];
        });

        $ok = $result->where('updated', true)->count() === count((array)$id);

        return response()->json([
            'ok' => $ok,
            'result' => $result,
            'description' => 'The user\'s role has been ' . ($ok ? 'successfully' : 'unsuccessfully') . ' updated.',
        ]);
    }

    public function set($id, $roles)
    {
        $result = $this->user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation($this->table);
            $pushed = false;
            if (! is_null($relation)) {
                $setted = (bool) $relation->update(['roles' => array_merge($relation->roles, (array)$roles)]);
            }
            return [
                'id' => $model->id,
                'setted' => $setted
            ];
        });

        $ok = $result->where('setted', true)->count() === count((array)$id);

        return response()->json([
            'ok' => $ok,
            'result' => $result,
            'description' => 'The user\'s role has been ' . ($ok ? 'successfully' : 'unsuccessfully') . ' setted.',
        ]);
    }

    public function unset($id, $roles)
    {
        $result = $this->user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation($this->table);
            $unsetted = false;
            if (! is_null($relation)) {
                $unsetted = (bool) $relation->update(['roles' => ['guest', ... array_diff($relation->roles, (array)$roles)]]);
            }
            return [
                'id' => $model->id,
                'unsetted' => $unsetted
            ];
        });

        $ok = $result->where('unsetted', true)->count() === count((array)$id);

        return response()->json([
            'ok' => $ok,
            'result' => $result,
            'description' => 'The user\'s role has been ' . ($ok ? 'successfully' : 'unsuccessfully') . ' unsetted.',
        ]);
    }
}
