<?php

namespace App\Http\Middleware;

use Closure;
use App\Enum\UserRole;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class VerifyRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        $excludedRoutes = [
            'admin/logout',
            'agent/logout',
        ];

        if (in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        if ($user) {
            $role = UserRole::fromId($user->role_id);

            if ($role === UserRole::ADMIN) {
                return $request->path() == 'admin' ? $next($request) : Redirect::to('/admin');
            }

            if ($role === UserRole::AGENT && $request->path() !== 'agent') {
                return Redirect::to('/agent');
            }
        }
        
        return redirect('/dashboard');
    }
}
