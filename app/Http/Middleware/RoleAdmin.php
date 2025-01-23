<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $allowed_roles = explode('|', $role);
        if (getAdminAuthRole() != null && !in_array(getAdminAuthRole(), $allowed_roles)) {
            abort(404);
        }
        return $next($request);
    }
}
