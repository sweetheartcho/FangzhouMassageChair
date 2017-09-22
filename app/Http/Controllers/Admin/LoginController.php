<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use DB;

class LoginController extends BaseController
{

    /** 登录页面 **/
    public function index(){
        return view('Admin.login.login');
    }

    /** 登录验证 **/
    public function login(Request $request){

        if ($request->isMethod('POST')) {
            // 处理表单提交的数据
            $data = $this->handle_label($request->input('User') );

            // select * from base_admin_info where account='' and password = md5(md5(''))

            $user = DB::table('base_admin_info')->where('account', $data['account'])->where('password', md5(md5($data['password'])))->where('is_state', '1')->first();

            if ('' == $user) {
                echo '1';
            } else {
                $token = time() . mt_rand(1000, 9999);
                $request->session()->put('token', $token, 20);
                $request->session()->put('account', $data['account']);
                $request->session()->put('admin_id', $user->admin_id);

                $results = DB::table('base_admin_info')->where('account', $data['account'])->where('is_state', '1')->update(['admin_key' => $token]);

                if($results){
                    echo '2';
                }
            }
        }

    }

    /** 退出 **/
    public function logout(Request $request){
        $request->session()->forget('token');
        $request->session()->forget('account');
        $request->session()->forget('admin_id');

        return redirect('jump')->with(['message' => '退出成功！', 'url' => '/Admin/Login/index', 'jumpTime' => 3, 'status' => false]);
    }
}
