<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\Merchant;
use DB;

class MerchantController extends BaseController
{
    // 贵宾厅列表页面
    public function index(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'merchant_name';
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

        $url = '';
        if (isset($_GET['search_name'])) {
            $url .= '&search_name=' . urlencode(html_entity_decode($_GET['search_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_phone'])) {
            $url .= '&search_phone=' . $_GET['search_phone'];
        }

        $pageSize = 10;

        $data = [
            'sort' => $sort,
            'search_name' => $search_name,
            'search_phone' => $search_phone
        ];

        $merchant = $this->getMerchantInfo($data);
        $merchantnum = $this->getMerchantNum($data);

        $sort_route[] = [
            'merchant_name' => url('Admin/Merchant/index') . '?sort=merchant_name' . $url,
            'merchant_phone' => url('Admin/Merchant/index') . '?sort=merchant_phone' . $url,
            'merchant_account' => url('Admin/Merchant/index') . '?sort=merchant_account' . $url,
            'merchant_create_date' => url('Admin/Merchant/index') . '?sort=create_date' . $url
        ];

        $item = array_splice($merchant, ($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $merchantnum, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_name=' . $search_name . '&search_phone=' . $search_phone,
            'pageName' => 'page',
        ]);

        $merchantinfo = $paginator->toArray()['data'];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '贵宾厅管理'],
            ['text' => '贵宾厅列表']
        ];

        return view('Admin.merchant.MerchantList', [
            'breadcrumbs' => $breadcrumbs,
            'merchantinfo' => $merchantinfo,
            'merchantnum' => $merchantnum,
            'paginator' => $paginator,
            'sort_route' => $sort_route,
            'search_name' => $search_name,
            'search_phone' => $search_phone,
            'sort' => $sort
        ]);
    }

    // 批量删除
    public function batchDelete(){
        $merchant_id = request()->input('selected');

        if (!empty($merchant_id)) {

            $fail_merchant = [];
            foreach ($merchant_id as $id) {
                $merchantname = Merchant::find($id)->value('merchant_name');
                $merchantinfo = Merchant::where('merchant_id', $id)->update(['merchant_state' => '0']);

                if ($merchantinfo == 0) {
                    $fail_merchant[] = $merchantname;
                }
            }

            if (empty($fail_merchant)) {
                return redirect('jump')->with(['message' => '批量删除成功!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
            } else {
                return redirect('jump')->with(['message' => implode(',', $fail_merchant) . '删除失败！', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
            }
        } else {
            return redirect('jump')->with(['message' => '请先选择要删除的数据！', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 删除
    public function delete($id){

        $merchantinfo = Merchant::where('merchant_id', $id)->update(['merchant_state' => '0']);

        if ($merchantinfo == 0) {
            return redirect('jump')->with(['message' => '删除失败!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '删除成功!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 获取贵宾厅列表数据
    public function getMerchantInfo($data = array()){

        $sql = "SELECT * FROM base_merchant";

        $whereList = [];
        if (!empty($data['search_name'])) {
            $whereList[] = " merchant_name LIKE '%" . $data['search_name'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " merchant_phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE " . implode(' AND', $whereList);
            $sql .= " AND merchant_state=1";
        } else {
            $sql .= " WHERE merchant_state=1";
        }

        if (request()->session()->has('merchant_id')) {
            $sql .= " AND merchant_id='" . request()->session()->get('merchant_id') . "'";
        }

        $sql .= " GROUP BY merchant_id";

        $sort_data = [
            'merchant_name',
            'merchant_phone',
            'merchant_account',
            'create_date'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY merchant_name DESC";
        }

        $merchant = DB::select($sql);
        return $merchant;
    }

    // 数据数量
    public function getMerchantNum($data = array()){
        $sql = "SELECT COUNT(merchant_id) AS total FROM base_merchant";

        $whereList = [];
        if (!empty($data['search_name'])) {
            $whereList[] = " merchant_name LIKE '%" . $data['search_name'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " merchant_phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE " . implode(' AND', $whereList);
            $sql .= " AND merchant_state=1";
        } else {
            $sql .= " WHERE merchant_state=1";
        }

        if (request()->session()->has('merchant_id')) {
            $sql .= " AND merchant_id='" . request()->session()->get('merchant_id') . "'";
        }

        $sort_data = [
            'merchant_name',
            'merchant_phone',
            'merchant_account',
            'create_date'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY merchant_name DESC";
        }

        $results = DB::select($sql);
        foreach ($results as $result) {
            return $result->total;
        }

    }

    // 编辑贵宾厅信息页面
    public function merchantEditIndex($id){

        $merchantinfo = Merchant::find($id);

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '贵宾厅管理'],
            ['text' => '贵宾厅列表'],
            ['text' => '修改贵宾厅信息']
        ];

        return view('Admin.merchant.MerchantEdit', [
            'merchantinfo' => $merchantinfo,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // 保存修改贵宾厅信息
    public function MerchantEdit(){
        if (request()->isMethod('POST')) {
            $merchant = $this->handle_label(request()->input('Merchant'));
            $merchant_id = request()->input('merchant_id');

            // 数据保留
            $this->saveSession($merchant);

            if ('' == $merchant['merchant_name'] || '' == $merchant['merchant_phone'] || '' == $merchant['merchant_account']) {
                return redirect('jump')->with(['message' => '贵宾厅名称，联系方式，账号不能为空！', 'url' => 'Admin/Merchant/merchantEditIndex/id/' . $merchant_id, 'jumpTime' => 3, 'status' => false]);
            } else {
                // 验证手机号
                $pattern = "/^1[34578]\d{9}$/";
                if (!preg_match($pattern, $merchant['merchant_phone'])) {
                    return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => 'Admin/Merchant/merchantEditIndex/id/' . $merchant_id, 'jumpTime' => 3, 'status' => false]);
                }

                $merchants = Merchant::where('merchant_id', $merchant_id)->get(['merchant_name', 'merchant_phone', 'merchant_password', 'merchant_account']);
                foreach ($merchants as $value) {
                    $merchantArr = $value;
                }

                if ($merchant['merchant_name'] == $merchantArr['merchant_name'] && $merchant['merchant_phone'] == $merchantArr['merchant_phone'] &&
                    md5(md5($merchant['merchant_password'])) == $merchantArr['merchant_password'] && $merchant['merchant_account'] == $merchantArr['merchant_account']) {
                    $this->forgetSession();
                    return redirect('jump')->with(['message' => '请注意：未修改任何信息', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
                } else {
                    $merchant['merchant_password'] = md5(md5($merchant['merchant_password']));

                    if ('' != $merchant['merchant_password']) {
                        $merchantinfo = Merchant::where('merchant_id', $merchant_id)->update($merchant);
                    } else {
                        $merchantinfo = Merchant::where('merchant_id', $merchant_id)->update(['merchant_name' => $merchant['name'], 'merchant_phone' => $merchant['phone'], 'merchant_account' => $merchant['account']]);
                    }

                    if ($merchantinfo == 0) {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改失败!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改成功!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
                    }
                }
            }
        }
    }

    // 添加贵宾厅页面
    public function addMerchantIndex(){

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '贵宾厅管理'],
            ['text' => '添加贵宾厅']
        ];

        return view('Admin.merchant.AddMerchant', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // 保存添加贵宾厅信息
    public function AddMerchant(){
        if (request()->isMethod('POST')) {
            $merchantinfo = $this->handle_label(request()->input('Merchant'));

            // 数据保留
            $this->saveSession($merchantinfo);

            if ('' == $merchantinfo['merchant_name'] || '' == $merchantinfo['merchant_phone'] || '' == $merchantinfo['merchant_account'] || '' == $merchantinfo['merchant_password']) {
                return redirect('jump')->with(['message' => '贵宾厅名称，联系方式，账号，密码不能为空！', 'url' => '/Admin/Merchant/addMerchantIndex', 'jumpTime' => 3, 'status' => false]);
            } else {

                // 验证手机号
                $pattern = "/^1[34578]\d{9}$/";
                if (!preg_match($pattern, $merchantinfo['merchant_phone'])) {
                    return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => '/Admin/Merchant/addMerchantIndex', 'jumpTime' => 3, 'status' => false]);
                }

                // 验证账号唯一
                $check_merchant_account = Merchant::where('merchant_account', $merchantinfo['merchant_account'])->first();
                if (!empty($check_merchant_account)) {
                    return redirect('jump')->with(['message' => '账号已存在!', 'url' => '/Admin/Merchant/addMerchantIndex', 'jumpTime' => 3, 'status' => false]);
                } else {
                    $merchantinfo['merchant_password'] = md5(md5($merchantinfo['merchant_password']));
                    $merchantinfo['create_user_id'] = request()->session()->get('admin_id');
                    $merchant = Merchant::create($merchantinfo);

                    if (!empty($merchant)) {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '添加成功!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '添加失败!', 'url' => '/Admin/Merchant/index', 'jumpTime' => 3, 'status' => false]);
                    }
                }
            }
        }
    }

    // 将表单信息保存至session实现数据保留
    public function saveSession($data){
        if (!empty($data)) {
            request()->session()->put('merchant_name', $data['merchant_name']);
            request()->session()->put('merchant_phone', $data['merchant_phone']);
            request()->session()->put('merchant_account', $data['merchant_account']);
            request()->session()->put('merchant_password', $data['merchant_password']);
        }
    }

    // 操作成功后清空部分session数据
    public function forgetSession(){
        request()->session()->forget('merchant_name');
        request()->session()->forget('merchant_phone');
        request()->session()->forget('merchant_account');
        request()->session()->forget('merchant_password');
    }
}
