<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (request()->session()->has('token') && request()->session()->has('account')) {
            $token = request()->session()->get('token');
            $account = request()->session()->get('account');

            $user = DB::table('base_admin_info')->where('account', $account)->value('admin_key');

            if ($user != $token) {
                //return redirect('Admin/Login/index');
                return redirect('jump')->with(['message' => '账号已登录，请重新登录！', 'url' => 'Admin/Login/index', 'jumpTime' => 3, 'status' => false]);
            }
        } else {
            //return redirect('Admin/Login/index');
            return redirect('jump')->with(['message' => '未登录，请重新登录！', 'url' => 'Admin/Login/index', 'jumpTime' => 3, 'status' => false]);
        }

        return $next($request);
    }
}
