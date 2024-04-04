<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasProfile
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        /**
         * @var User $user
         */
        $user = auth()->user();
        if($user == null){
            return $response;
        }

        if ($user->profiles()->exists()) {
            return $response;
        }

        return redirect('/profile/new');


//        if (! Profile::query()->firstWhere('brand_id', auth()->id()) ) {
//            return redirect('/profile/new');
//        }
//
//        return $next($request);
    }
}
