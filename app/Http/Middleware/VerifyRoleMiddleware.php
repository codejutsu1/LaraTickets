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

        if ($user) {
            $role = UserRole::fromId($user->role_id);

            if ($role === UserRole::USER) {
                return Redirect::to('/dashboard');
            }

            if ($role === UserRole::AGENT) {
                return Redirect::to('/agent');
            }
        }
        
        return $next($request);
    }
}
