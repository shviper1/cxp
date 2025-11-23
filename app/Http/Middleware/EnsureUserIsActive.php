<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('filament.admin.auth.login')->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
            }

            if ($user->status === 'suspended' && $user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('filament.admin.auth.login')->withErrors([
                    'email' => 'Your account is suspended until ' . $user->suspended_until->format('Y-m-d H:i'),
                ]);
            }
            
            // Auto-activate if suspension expired
            if ($user->status === 'suspended' && ! $user->isSuspended()) {
                $user->update(['status' => 'active', 'suspended_until' => null]);
            }
        }

        return $next($request);
    }
}
