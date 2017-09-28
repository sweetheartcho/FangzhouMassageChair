<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use DB;

class LoginController extends BaseController
{

    // 管理员登录页面
    public function AdminIndex(){
        return view('Admin.login.AdminLogin');
    }

    // 管理员登录验证
    public function AdminLogin(Request $request){

        if ($request->isMethod('POST')) {
            // 处理表单提交的数据
            $data = $this->handle_label($request->input('User'));

            $user = DB::table('base_admin_info')->where('account', $data['account'])->where('password', md5(md5($data['password'])))->where('is_state', '1')->first();

            if (NULL == $user) {
                echo '1';
            } else {
                $token = time() . mt_rand(1000, 9999);
                $request->session()->put('token', $token);
                $request->session()->put('account', $data['account']);
                $request->session()->put('admin_id', $user->admin_id);
                $request->session()->put('authority_id', $user->authority_id);
                $request->session()->put('markup', 'admin');

                $results = DB::table('base_admin_info')->where('account', $data['account'])->where('is_state', '1')->update(['admin_key' => $token]);

                if($results){
                    echo '2';
                }
            }
        }

    }

    // 管理员退出
    public function AdminLogout(Request $request){
        $request->session()->forget('token');
        $request->session()->forget('account');
        $request->session()->forget('admin_id');
        $request->session()->forget('authority_id');
        $request->session()->forget('markup');

        return redirect('jump')->with(['message' => '退出成功！', 'url' => '/Admin/Login/AdminIndex', 'jumpTime' => 3, 'status' => false]);
    }

    // 贵宾厅登录页面
    public function MerchantIndex(){

        return view('Admin.login.MerchantLogin');
    }

    // 贵宾厅登录验证
    public function MerchantLogin(Request $request){
        if ($request->isMethod('POST')) {
            $data = $this->handle_label($request->input('Merchant'));

            $merchant = DB::table('base_merchant')->where('merchant_account', $data['account'])->where('merchant_password', md5(md5($data['password'])))->where('merchant_state', '1')->first();

            if (NULL == $merchant) {
                echo '1';
            } else {
                $token = time() . mt_rand(1000, 9999);
                $request->session()->put('token', $token);
                $request->session()->put('account', $data['account']);
                $request->session()->put('authority_id', $merchant->authority_id);
                $request->session()->put('merchant_id', $merchant->merchant_id);

                $results = DB::table('base_company_info')->where('merchant_id', "$merchant->merchant_id")->get();
                foreach($results as $result){
                    $id[] = $result->company_id;
                }

                $request->session()->put('company_id', $id);


                echo '2';
            }
        }
    }

    // 贵宾厅退出
    public function MerchantLogout(Request $request){
        $request->session()->forget('token');
        $request->session()->forget('account');
        $request->session()->forget('authority_id');
        $request->session()->forget('company_id');
        $request->session()->forget('merchant_id');

        return redirect('jump')->with(['message' => '退出成功！', 'url' => '/Admin/Login/MerchantIndex', 'jumpTime' => 3, 'status' => false]);
    }

    // 商户登录页面
    public function CompanyIndex(){

        return view('Admin.login.CompanyLogin');
    }

    // 商户登录验证
    public function CompanyLogin(Request $request){
        if ($request->isMethod('POST')) {
            $data = $this->handle_label($request->input('Company'));

            $company = DB::table('base_company_info')->where('company_account', $data['account'])->where('company_password', md5(md5($data['password'])))->where('company_state', '1')->first();

            if (NULL == $company) {
                echo '1';
            } else {
                $token = time() . mt_rand(1000, 9999);
                $request->session()->put('token', $token);
                $request->session()->put('account', $data['account']);
                $request->session()->put('authority_id', $company->authority_id);

                $companyinfo = DB::table('base_company_info')->where('company_account', $data['account'])->where('company_state', '1')->first();

                $request->session()->put('company', $companyinfo->company_id);

                echo '2';
            }
        }
    }

    // 商户退出
    public function CompanyLogout(Request $request){
        $request->session()->forget('token');
        $request->session()->forget('account');
        $request->session()->forget('authority_id');
        $request->session()->forget('company');

        return redirect('jump')->with(['message' => '退出成功！', 'url' => '/Admin/Login/CompanyIndex', 'jumpTime' => 3, 'status' => false]);
    }
}
