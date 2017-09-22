<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\CompanyInfo;
use App\Model\Merchant;
use App\Model\UserCardOperation;
use App\Model\Companyphtots;
use DB;

class CompanyController extends BaseController
{
    // 商户列表页面
    public function index(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'company_name';
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        if (isset($_GET['search_name'])) {
            $search_name = $_GET['search_name'];
        } else {
            $search_name = '';
        }

        if (isset($_GET['search_phone'])) {
            $search_phone = $_GET['search_phone'];
        } else {
            $search_phone = '';
        }

        if (isset($_GET['search_abbreviation'])) {
            $search_abbreviation = $_GET['search_abbreviation'];
        } else {
            $search_abbreviation = '';
        }

        $url = '';
        if (isset($_GET['search_name'])) {
            $url .= '&search_name=' . urlencode(html_entity_decode($_GET['search_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_phone'])) {
            $url .= '&search_phone=' . $_GET['search_phone'];
        }

        if (isset($_GET['search_abbreviation'])) {
            $url .= '&search_abbreviation=' . urlencode(html_entity_decode($_GET['search_abbreviation'], ENT_QUOTES, 'UTF-8'));
        }

        $sort_route[] = [
            'company_name'         => url('Admin/Company/index') . '?sort=company_name' . $url,
            'company_abbreviation' => url('Admin/Company/index') . '?sort=company_abbreviation' . $url,
            'company_phone'        => url('Admin/Company/index') . '?sort=company_phone' . $url,
            'company_longitude'    => url('Admin/Company/index') . '?sort=company_longitude' . $url,
            'company_latitude'     => url('Admin/Company/index') . '?sort=company_latitude' . $url,
            'company_create_date'  => url('Admin/Company/index') . '?sort=create_date' . $url
        ];

        $pageSize = 10;

        $data = [
            'sort'                => $sort,
            'search_name'         => $search_name,
            'search_phone'        => $search_phone,
            'search_abbreviation' => $search_abbreviation,
        ];

        $company = $this->getCompanyInfo($data);
        $companynum = $this->getCompanyNum($data);

        $path = Paginator::resolveCurrentPath();
        $path .= '?sort=' . $sort . '&search_name=' . $search_name . '&search_phone=' . $search_phone . '&search_abbreviation=' . $search_abbreviation;

        $item = array_splice($company,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $companynum, $pageSize, $page, [
            'path'     => $path, //Paginator::resolveCurrentPath(),//Paginator::setPath('Admin/Company/index'),
            'pageName' => 'page',
        ]);

        $companyinfo = $paginator->toArray()['data'];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '会员管理'],
            ['text' => '商户管理'],
        ];

        return view('Admin.company.CompanyList',[
            'breadcrumbs'         => $breadcrumbs,
            'companyinfo'         => $companyinfo,
            'companynum'          => $companynum,
            'paginator'           => $paginator,
            'search_name'         => $search_name,
            'search_phone'        => $search_phone,
            'search_abbreviation' => $search_abbreviation,
            'sort_route'          => $sort_route,
            'sort'                => $sort
        ]);
    }

    // 删除商户
    public function delete($id) {
        $companyinfo = CompanyInfo::where('company_id', $id)->update(['company_state' => '-1']);

        if ($companyinfo > 0) {
            return redirect('jump')->with(['message' => '删除成功！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '删除失败！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 批量删除商户
    public function batchDelete() {
        if (request()->isMethod('POST')) {
            $company_ids = request()->input('selected');

            if (!empty($company_ids)) {

                foreach ($company_ids as $company_id) {
                    $companyname = CompanyInfo::find($company_id)->value('company_name');
                    $companyinfo = CompanyInfo::where('company_id', $company_id)->update(['company_state' => '-1']);

                    if ($companyinfo == 0) {
                        $fail_company_name[] = $companyname;
                    }
                }

                if (empty($fail_company_name)) {
                    return redirect('jump')->with(['message' => '批量删除成功！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                }else{
                    return redirect('jump')->with(['message' => implode(',', $fail_company_name) . '删除失败！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                }

            } else {
                return redirect('jump')->with(['message' => '请先选择要删除的数据！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
            }
        }
    }

    // 查询商户列表
    public function getCompanyInfo($data = array()) {

        $sql = "SELECT * FROM base_company_info WHERE";

        $whereList = [];
        if (!empty($data['search_name'])) {
            $whereList[] = " company_name LIKE '%" . $data['search_name'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " company_phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (!empty($data['search_abbreviation'])) {
            $whereList[] = " company_abbreviation LIKE '%" . strtoupper($data['search_abbreviation']) . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= implode(' AND', $whereList);
            $sql .= " AND company_state=1";
        } else {
            $sql .= " company_state=1";
        }

        $sql .= " GROUP BY company_id ";

        $sort_data = [
            'company_name',
            'company_abbreviation',
            'company_phone',
            'company_longitude',
            'company_latitude',
            'create_date'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY company_name DESC";
        }

        $company = DB::select($sql);

        return $company;

    }

    // 数据数量
    public function getCompanyNum($data = array()){
        $sql = "SELECT COUNT(company_id) AS total FROM base_company_info WHERE";

        $whereList = [];
        if (!empty($data['search_name'])) {
            $whereList[] = " company_name LIKE '%" . $data['search_name'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " company_phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (!empty($data['search_abbreviation'])) {
            $whereList[] = " company_abbreviation LIKE '%" . strtoupper($data['search_abbreviation']) . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= implode(' AND', $whereList);
            $sql .= " AND company_state=1";
        } else {
            $sql .= " company_state=1";
        }

        $sort_data = [
            'company_name',
            'company_abbreviation',
            'company_phone',
            'company_longitude',
            'company_latitude',
            'create_date'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY company_name DESC";
        }

        $results = DB::select($sql);
        foreach ($results as $result) {
            return $result->total;
        }
    }

    // 编辑商户页面
    public function companyEditIndex($id){
        $companyinfo = CompanyInfo::find($id);
        $companyphoto = Companyphtots::where('company_id', $id)->get();

        // 使用时间
        $time = UserCardOperation::where('company_id', $id)->where('operate_type', '2')->groupBy('company_id')->sum('use_time');
        $use_time = $this->handletime($time);

        // 贵宾厅名称
        $merchantname = Merchant::where('merchant_state', 1)->get(['merchant_name','merchant_id']);

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '会员管理'],
            ['text' => '商户管理'],
            ['text' => '修改商户信息']
        ];

        return view('Admin.company.CompanyEdit', [
            'breadcrumbs'  => $breadcrumbs,
            'companyinfo'  => $companyinfo,
            'companyphoto' => $companyphoto,
            'merchantname' => $merchantname,
            'usetime'      => $use_time
        ]);
    }

    // 保存商户编辑信息
    public function companyEdit(){
        if (request()->isMethod('POST')) {
            $company = $this->handle_label(request()->input('Company'));
            $company_id = request()->input('company_id');
            $company_photo = request()->input('company_photo');

            $this->saveSession($company);
            request()->session()->put('company_price', $company['company_price']);

            if ('' == $company['company_name'] || '' == $company['company_abbreviation'] || '' == $company['company_phone'] || '' == $company['company_longitude'] ||
                '' == $company['company_latitude']||'' == $company['company_account']) {
                return redirect('jump')->with(['message' => '商户名称，商户简称，手机号，地址，账号不能为空!', 'url' => '/Admin/Company/companyEditIndex/id/'.$company_id, 'jumpTime' => 3, 'status' => false]);
            } else {

                $pattern = "/^1[34578]\d{9}$/";
                if (!preg_match($pattern, $company['company_phone'])) {
                    return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => 'Admin/Merchant/merchantEditIndex/id/'.$company_id, 'jumpTime' => 3, 'status' => false]);
                }

                $companymes = CompanyInfo::find($company_id);
                if ($company['company_name'] == $companymes['company_name'] && $company['company_abbreviation'] == $companymes['company_abbreviation'] &&
                    $company['company_phone'] == $companymes['company_phone'] && $company['company_longitude'] == $companymes['company_longitude'] &&
                    $company['company_latitude'] == $companymes['company_latitude'] && $company['company_account'] == $companymes['company_account'] &&
                    $company['company_password'] == '' && $company['company_price'] == $companymes['company_price'] &&
                    $company['company_description'] == $companymes['company_description'] && $company['company_bar_code'] == $companymes['company_bar_code'] &&
                    $company['merchant_id'] == $companymes['merchant_id'] && $company_photo == '') {

                    $this->forgetSession();
                    request()->session()->forget('company_price');
                    return redirect('jump')->with(['message' => '请注意：未修改任何信息!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                } else {
                    // 处理贵宾室
                    if ('*' == $company['merchant_id']) {
                        unset($company['merchant_id']);
                    }

                    // 处理密码
                    if ('' == $company['company_password']) {
                        unset($company['company_password']);
                    }else{
                        $company['company_password'] = md5(md5($company['company_password']));
                    }

                    $companyinfo = CompanyInfo::where('company_id', $company_id)->update($company);

                    if ('' == $company_photo) {
                        if ($companyinfo == 0) {
                            return redirect('jump')->with(['message' => '修改失败!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                        } else {
                            $this->forgetSession();
                            request()->session()->forget('company_price');
                            return redirect('jump')->with(['message' => '修改成功!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                        }
                    } else {
                        $photos = explode(',', $company_photo);
                        $company_info = [
                            'company_id'  => $company_id,
                            'create_date' => time()
                        ];

                        foreach ($photos as $photo) {
                            $image = basename($photo);
                            $company_info['image'] = 'uploads/' . $image;
                            $results = Companyphtots::create($company_info);

                            if (empty($results)) {
                                $failphoto[] = $image;
                            }
                        }

                        if (!empty($failphoto) || $companyinfo == 0) {
                            return redirect('jump')->with(['message' => '信息修改失败，' . explode(',', $failphoto) . '修改失败！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                        } else {
                            $this->forgetSession();
                            request()->session()->forget('company_price');
                            return redirect('jump')->with(['message' => '修改成功!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                        }
                    }
                }
            }
        }
    }

    // 删除已显示图片
    public function deleteImg(){
        if (request()->isMethod('POST')) {
            $src = request()->input('src');
            $company_photo = Companyphtots::where('image', $src)->delete();

            if (0 == $company_photo) {
                return response()->json('删除失败');
            } else {
                return response()->json('删除成功');
            }
        }
    }

    // 处理商户使用时间
    public function handletime($time){
        if (null != $time) {
            $hour = floor($time / 60);
            $minute = floor($time - $hour * 60);

            if ($hour == 0) {
                return $minute . '分';
            } else {
                return $hour . '时' . $minute . '分';
            }

        } else {
            return '';
        }
    }

    // 添加商户页面
    public function addCompanyIndex(){
        // 获取贵宾厅
        $merchantname = Merchant::where('merchant_state', '1')->get(['merchant_name','merchant_id']);

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '会员管理'],
            ['text' => '添加商户'],
        ];

        return view('Admin.company.AddCompany', [
            'breadcrumbs'  => $breadcrumbs,
            'merchantname' => $merchantname
        ]);
    }

    // 保存商户添加信息
    public function addCompany(){
        if (request()->isMethod('POST')) {
            $companyinfo = $this->handle_label(request()->input('Company'));
            $company_photo = request()->input('company_photo');
            $company_confirm_pwd = trim(htmlentities(request()->input('confirm_password')));

            $this->saveSession($companyinfo);
            request()->session()->put('confirm_password', $company_confirm_pwd);

            if ('' == $companyinfo['company_name'] || '' == $companyinfo['company_abbreviation'] || '' == $companyinfo['company_phone'] ||
                '' == $companyinfo['company_longitude'] || '' == $companyinfo['company_latitude'] || '' == $companyinfo['company_account'] || '' == $companyinfo['company_password']) {
                return redirect('jump')->with(['message' => '商户名称，商户简称，手机号，地址，账号，密码不能为空!', 'url' => '/Admin/Company/addCompanyIndex', 'jumpTime' => 3, 'status' => false]);
            } else {
                // 验证账号唯一
                /*$company = CompanyInfo::where('company_account', $companyinfo['company_account'])->get();
                if (!empty($company)) {
                    return redirect('jump')->with(['message' => '账号已存在!', 'url' => '/Admin/Company/addCompanyIndex', 'jumpTime' => 3, 'status' => false]);
                }*/

                // 验证手机号
                $pattern = "/^1[34578]\d{9}$/";
                if (!preg_match($pattern, $companyinfo['company_phone'])) {
                    return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => '/Admin/Company/addCompanyIndex', 'jumpTime' => 3, 'status' => false]);
                }

                // 验证两次密码是否一致
                if ($company_confirm_pwd != $companyinfo['company_password']) {
                    return redirect('jump')->with(['message' => '两次输入的密码不一致!', 'url' => '/Admin/Company/addCompanyIndex', 'jumpTime' => 3, 'status' => false]);
                }

                // 处理贵宾厅
                if ('*' == $companyinfo['merchant_id']) {
                    unset($companyinfo['merchant_id']);
                }

                // 处理密码
                if (isset($companyinfo['company_password']) && $companyinfo['company_password'] != '') {
                    $companyinfo['company_password'] = md5(md5($companyinfo['company_password']));
                }

                // 默认添加当前时间戳
                $companyinfo['create_date'] = time();

                $companymes = CompanyInfo::create($companyinfo);
                $company_id = $companymes->company_id;

                if ($company_photo != '') {
                    $photos = explode(',', $company_photo);
                    $company = [
                        'company_id'  => $company_id,
                        'create_date' => time()
                    ];

                    foreach ($photos as $photo) {
                        $image = basename($photo);
                        $company['image'] = 'uploads/' . $image;
                        $results = Companyphtots::create($company);

                        if (empty($results)) {
                            $failphoto[] = $image;
                        }
                    }

                    if (!empty($failphoto) || $companyinfo == 0) {
                        return redirect('jump')->with(['message' => '信息添加失败，' . explode(',', $failphoto) . '添加失败！', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        request()->session()->forget('company_price');
                        return redirect('jump')->with(['message' => '添加成功!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                    }

                } else {
                    if (empty($companymes)) {
                        return redirect('jump')->with(['message' => '添加失败!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        request()->session()->forget('confirm_password');
                        return redirect('jump')->with(['message' => '添加成功!', 'url' => '/Admin/Company/index', 'jumpTime' => 3, 'status' => false]);
                    }
                }
            }
        }
    }

    // 将表单信息保存至session实现数据保留
    public function saveSession($data){
        if (!empty($data)) {
            request()->session()->put('company_name', $data['company_name']);
            request()->session()->put('company_abbreviation', $data['company_abbreviation']);
            request()->session()->put('company_phone', $data['company_phone']);
            request()->session()->put('merchant_id', $data['merchant_id']);
            request()->session()->put('company_longitude', $data['company_longitude']);
            request()->session()->put('company_latitude', $data['company_latitude']);
            request()->session()->put('company_account', $data['company_account']);
            request()->session()->put('company_password', $data['company_password']);
            request()->session()->put('company_description', $data['company_description']);
            request()->session()->put('company_bar_code', $data['company_bar_code']);
        }
    }

    // 操作成功后清空部分session数据
    public function forgetSession(){
        request()->session()->forget('company_name');
        request()->session()->forget('company_abbreviation');
        request()->session()->forget('company_phone');
        request()->session()->forget('merchant_id');
        request()->session()->forget('company_longitude');
        request()->session()->forget('company_latitude');
        request()->session()->forget('company_account');
        request()->session()->forget('company_password');
        request()->session()->forget('company_description');
        request()->session()->forget('company_bar_code');
    }
}
