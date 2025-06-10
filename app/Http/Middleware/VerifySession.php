<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;
use App\Models\Admin;
use App\Models\User;

class VerifySession
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('temp_admin_id')) {
            if (!Admin::find($request->session()->get('temp_admin_id'))) {
                $request->session()->forget('temp_admin_id');
                return redirect()->route('admin.login')
                    ->withErrors(['session' => 'Session invalide']);
            }
        }

        return $next($request);
    }
}