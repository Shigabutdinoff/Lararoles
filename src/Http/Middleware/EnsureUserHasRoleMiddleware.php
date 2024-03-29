<?php

namespace Shigabutdinoff\Lararoles\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Shigabutdinoff\Lararoles\Models\RoleModel;
use Shigabutdinoff\Lararoles\Roles;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $hasRoles = RoleModel::whereRelation('user', 'id', $request->user()->id)
            ->whereJsonContains('roles', $roles)
            ->exists();

        $my_roles = Roles::user();

        var_dump($my_roles->setHasOneRelation(RoleModel::class));

//        var_dump(json_decode(json_encode(RoleModel::with('user')->whereRelationJsonContains('user', 'id', 1)->get())));

        if ( $hasRoles) {
            return response()->json([
                'ok' => false,
                'result' => false,
                'description' => 'You don\'t have permission to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
