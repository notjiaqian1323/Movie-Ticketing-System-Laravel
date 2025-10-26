<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\UserTypes\UserFactory;

class AdminMiddleware
{
  public function handle($request, Closure $next)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $userFactory = app(\App\UserTypes\UserFactory::class);
    $user = $userFactory->create(Auth::user()->role);

    if (!$user->canAccessAdminPanel() || Auth::user()->status !== 'active') {
      abort(403, 'Unauthorized access.');
    }

    return $next($request);
  }
}
