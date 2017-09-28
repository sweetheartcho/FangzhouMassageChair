<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\CompanyWaiter;
use DB;

class WaiterController extends BaseController
{
    // 服务员查看页面
    public function index($id) {

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'code';
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        if (isset($_GET['search_code'])) {
            $search_code = $_GET['search_code'];
        } else {
            $search_code = '';
        }

        if (isset($_GET['search_waiter_name'])) {
            $search_waiter_name = $_GET['search_waiter_name'];
        } else {
            $search_waiter_name = '';
        }

        $url = '';
        if (isset($_GET['search_code'])) {
            $url .= '&search_code=' . urlencode(html_entity_decode($_GET['search_code'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_waiter_name'])) {
            $url .= '&search_waiter_name=' . urlencode(html_entity_decode($_GET['search_waiter_name'], ENT_QUOTES, 'UTF-8'));
        }

        $sort_route[] = [
            'code'             => url('Admin/Waiter/index') . '/id/' . $id . '?sort=code' . $url,
            'waiter_name'      => url('Admin/Waiter/index') . '/id/' . $id . '?sort=waiter_name' . $url,
            'waiter_telephone' => url('Admin/Waiter/index') . '/id/' . $id . '?sort=waiter_telephone' . $url
        ];

        $data = [
            'id'                 => $id,
            'sort'               => $sort,
            'search_code'        => $search_code,
            'search_waiter_name' => $search_waiter_name
        ];

        $pageSize = 10;

        $waiter = $this->getWaiterInfo($data);
        $waiternum = $this->getWaiterNum($data);

        $item = array_splice($waiter,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $waiternum, $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_code=' . $search_code . '&search_waiter_name=' . $search_waiter_name,
            'pageName' => 'page',
        ]);

        $waiterinfo = $paginator->toArray()['data'];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '信息管理'],
            ['text' => '商户管理'],
            ['text' => '编辑商户'],
            ['text' => '查看服务员信息'],
        ];

        return view('Admin.waiter.WaiterList', [
            'breadcrumbs'         => $breadcrumbs,
            'waiterinfo'          => $waiterinfo,
            'waiternum'           => $waiternum,
            'paginator'           => $paginator,
            'sort'                => $sort,
            'sort_route'          => $sort_route,
            'search_code'         => $search_code,
            'search_waiter_name'  => $search_waiter_name,
            'id'                  => $id
        ]);
    }

    // 查询服务员列表
    public function getWaiterInfo($data = array()){

        $sql = "SELECT * FROM base_company_waiter";

        $whereList = [];
        if (!empty($data['search_code'])) {
            $whereList[] = " code LIKE '%" . $data['search_code'] . "%'";
        }

        if (!empty($data['search_waiter_name'])) {
            $whereList[] = " waiter_name LIKE '%" . $data['search_waiter_name'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
            $sql .= " AND company_id=" . $data['id'] . " AND waiter_state='1'";
        } else {
            $sql .= " WHERE company_id=" . $data['id'] . " AND waiter_state='1'";
        }

        $sort_data = [
            'code',
            'waiter_name',
            'waiter_telephone'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY code DESC";
        }

        $waiter = DB::select($sql);
        return $waiter;
    }

    // 数据数量
    public function getWaiterNum($data = array()) {
        $sql = "SELECT COUNT(waiter_id) AS total FROM base_company_waiter";

        $whereList = [];
        if (!empty($data['search_code'])) {
            $whereList[] = " code LIKE '%" . $data['search_code'] . "%'";
        }

        if (!empty($data['search_waiter_name'])) {
            $whereList[] = " waiter_name LIKE '%" . $data['search_waiter_name'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
            $sql .= " AND company_id=" . $data['id'] . " AND waiter_state='1'";
        } else {
            $sql .= " WHERE company_id=" . $data['id'] . " AND waiter_state='1'";
        }

        $sort_data = [
            'code',
            'waiter_name',
            'waiter_telephone'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY waiter_id DESC";
        }

        $results = DB::select($sql);
        foreach ($results as $result) {
            return $result->total;
        }
    }

    // 删除服务员
    public function delete($id){
        $company_id = request()->route('id');
        $waiter = CompanyWaiter::where('waiter_id', $id)->update(['waiter_state' => '-1']);

        if (0 == $waiter) {
            return redirect('jump')->with(['message' => '删除失败!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '删除成功!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 批量删除服务员
    public function batchDelete() {
        if (request()->isMethod('POST')) {
            $waiter_id = request()->input('selected');
            $company_id = request()->input('company_id');

            $fail_waiter_name = [];
            if (!empty($waiter_id)) {
                foreach ($waiter_id as $id) {
                    $waitername = CompanyWaiter::where('waiter_id', $id)->value('waiter_name');
                    $waiterinfo = CompanyWaiter::where('waiter_id', $id)->update(['waiter_state' => '-1']);

                    if ($waiterinfo == 0) {
                        $fail_waiter_name[] = $waitername;
                    }
                }

                if (empty($fail_waiter_name)) {
                    return redirect('jump')->with(['message' => '批量删除成功!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                } else {
                    return redirect('jump')->with(['message' => implode(',', $fail_waiter_name) . '删除失败!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                }
            } else {
                return redirect('jump')->with(['message' => '请选择要删除的数据!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
            }
        }
    }

    // 服务员编辑页面
    public function waiterEditIndex($id){
        $waiterinfo = CompanyWaiter::find($id);

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '信息管理'],
            ['text' => '商户管理'],
            ['text' => '编辑商户'],
            ['text' => '查看服务员信息'],
            ['text' => '编辑服务员信息']
        ];

        return view('Admin.waiter.WaiterEdit', [
            'breadcrumbs' => $breadcrumbs,
            'waiterinfo'  => $waiterinfo
        ]);
    }

    // 保存服务员编辑信息
    public function waiterEdit() {
        if (request()->isMethod('POST')) {
            $waiter = $this->handle_label(request()->input('Waiter'));
            $waiter_id = request()->input('waiter_id');
            $company_id = request()->input('company_id');

            $this->saveSession($waiter);

            if ('' == $waiter['waiter_name'] || '' == $waiter['waiter_telephone']) {
                return redirect('jump')->with(['message' => '服务员姓名，手机号不能为空!', 'url' => '/Admin/Waiter/waiterEditIndex/id/' . $waiter_id, 'jumpTime' => 3, 'status' => false]);
            } else {
                $waiter_info = CompanyWaiter::find($waiter_id);
                if ($waiter['waiter_name'] == $waiter_info['waiter_name'] && $waiter['waiter_telephone'] == $waiter_info['waiter_telephone']) {
                    $this->forgetSession();
                    return redirect('jump')->with(['message' => '请注意：未修改任何信息!', 'url' => '/Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                } else {
                    // 验证手机号
                    $pattern = "/^1[34578]\d{9}$/";
                    if (!preg_match($pattern, $waiter['waiter_telephone'])) {
                        return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => 'Admin/Waiter/waiterEditIndex/id/'.$waiter_id, 'jumpTime' => 3, 'status' => false]);
                    }

                    $waiterinfo = CompanyWaiter::where('waiter_id', $waiter_id)->update($waiter);
                    if (0 == $waiterinfo) {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改失败!', 'url' => 'Admin/Waiter/waiterEditIndex/id/'.$waiter_id, 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改成功!', 'url' => 'Admin/Waiter/index/id/'.$company_id, 'jumpTime' => 3, 'status' => false]);
                    }
                }
            }
        }
    }

    // 服务员添加页面
    public function waiterAddIndex($id){

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '信息管理'],
            ['text' => '商户管理'],
            ['text' => '编辑商户'],
            ['text' => '添加服务员信息']
        ];

        return view('Admin.waiter.AddWaiter', [
            'breadcrumbs' => $breadcrumbs,
            'company_id'  => $id
        ]);
    }

    // 保存服务员添加信息
    public function waiterAdd(){
        if (request()->isMethod('POST')) {
            $waiter = $this->handle_label(request()->input('Waiter'));
            $company_id = request()->input('company_id');
            $waiter['company_id'] = $company_id;
            $waiter['create_date'] = time();

            request()->session()->put('code', $waiter['code']);
            request()->session()->put('waiter_name', $waiter['waiter_name']);
            request()->session()->put('waiter_telephone', $waiter['waiter_telephone']);

            if ('' == $waiter['code'] || '' == $waiter['waiter_name'] || '' == $waiter['waiter_telephone']) {
                return redirect('jump')->with(['message' => '编号，服务员姓名，手机号不能为空!', 'url' => '/Admin/Waiter/waiterAddIndex/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
            } else {
                // 验证手机号
                $pattern = "/^1[34578]\d{9}$/";
                if (!preg_match($pattern, $waiter['waiter_telephone'])) {
                    return redirect('jump')->with(['message' => '请填写正确格式的手机号!', 'url' => '/Admin/Waiter/waiterAddIndex/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                }

                $waiterinfo = CompanyWaiter::create($waiter);

                if (empty($waiterinfo)) {
                    request()->session()->forget('code');
                    request()->session()->forget('waiter_name');
                    request()->session()->forget('waiter_telephone');
                    return redirect('jump')->with(['message' => '添加失败!', 'url' => 'Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                } else {
                    request()->session()->forget('code');
                    request()->session()->forget('waiter_name');
                    request()->session()->forget('waiter_telephone');
                    return redirect('jump')->with(['message' => '添加成功!', 'url' => 'Admin/Waiter/index/id/' . $company_id, 'jumpTime' => 3, 'status' => false]);
                }
            }
        }
    }

    // 将表单信息保存至session实现数据保留
    public function saveSession($data){
        if (!empty($data)) {
            request()->session()->put('waiter_name', $data['waiter_name']);
            request()->session()->put('waiter_telephone', $data['waiter_telephone']);
        }
    }

    // 操作成功后清空部分session数据
    public function forgetSession(){
        request()->session()->forget('waiter_name');
        request()->session()->forget('waiter_telephone');
    }
}
