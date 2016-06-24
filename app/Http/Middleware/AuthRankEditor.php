<?php

namespace App\Http\Middleware;

use App\Models\UserRankEnum;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthRankEditor
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //If the user doesn't have a high enough rank
        if (!Auth::check() || !Auth::user()->isMinimumRank(UserRankEnum::Editor)) {
            return redirect('/');
        }

        return $next($request);
    }
}
