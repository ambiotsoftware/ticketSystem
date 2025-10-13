<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class RoleMiddleware
{
    /**
     * Uso: ->middleware(['auth', 'role:admin,client'])
     */
    public function handle(Request $request, Closure $next, $roles = ''): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No estás autenticado.');
        }

        // Convierte la cadena de roles ("admin,client") a array
        $allowedRoles = is_string($roles)
            ? array_map('trim', explode(',', $roles))
            : (array) $roles;

        // Convierte el enum a su valor
        $userRole = $user->role instanceof UserRole ? $user->role->value : $user->role;

        // Debug temporal (puedes comentar si quieres)
        // dd(['userRole' => $userRole, 'allowedRoles' => $allowedRoles]);

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
