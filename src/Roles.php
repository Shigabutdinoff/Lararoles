<?php

namespace Shigabutdinoff\Lararoles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Shigabutdinoff\JsonRelation\JsonRelation;
use Shigabutdinoff\Lararoles\Models\RoleModel;

class Roles extends Controller
{
    protected static function table()
    {
        return RoleModel::getModel()->getTable();
    }

    /**
     * Get the user Model use JsonRelation.
     */
    public static function user()
    {
        return JsonRelation::addToModel(User::class)
//            ->whereNotNull('id')
//            ->hasOneMacro(RoleModel::class)
            /*->with(Roles::table())*/;
    }

    public static function update($id, $roles)
    {
        $result = Roles::user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation(Roles::table());
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

    public static function set($id, $roles)
    {
        $result = Roles::user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation(Roles::table());
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

    public static function unset($id, $roles)
    {
        $result = Roles::user()->find((array)$id)->map(function ($model) use ($roles) {
            $relation = $model->getRelation(Roles::table());
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
