<?php

namespace Shigabutdinoff\Lararoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shigabutdinoff\Lararoles\Models\RoleModel;
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

        if (! $hasRoles) {
            return response()->json([
                'ok' => false,
                'result' => false,
                'description' => 'You don\'t have permission to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
