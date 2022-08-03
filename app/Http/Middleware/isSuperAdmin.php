<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSuperAdmin
{
    const SUPER_ADMIN_ROLE = 3;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        $userId = auth()->user()->id;
        $user = User::find($userId);
        if (!$user->roles->contains(self::SUPER_ADMIN_ROLE)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you dont have permission'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $next($request);
        
    }
}
