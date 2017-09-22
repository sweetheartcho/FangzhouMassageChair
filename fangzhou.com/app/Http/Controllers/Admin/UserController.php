<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\UserInfo;
use DB;

class UserController extends BaseController
{
    // 页面
    public function index(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'nickname';
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        if (isset($_GET['search_nickname'])) {
            $search_nickname = $_GET['search_nickname'];
        } else {
            $search_nickname = '';
        }

        if (isset($_GET['search_phone'])) {
            $search_phone = $_GET['search_phone'];
        } else {
            $search_phone = '';
        }

        if (isset($_GET['search_token'])) {
            $search_token = $_GET['search_token'];
        } else {
            $search_token = '';
        }

        if (isset($_GET['search_state'])) {
            $search_state = $_GET['search_state'];
        } else {
            $search_state = '';
        }

        $url = '';

        if (isset($_GET['search_nickname'])) {
            $url .= '&search_nickname=' . urlencode(html_entity_decode($_GET['search_nickname'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_phone'])) {
            $url .= '&search_phone=' . $_GET['search_phone'];
        }

        if (isset($_GET['search_token'])) {
            $url .= '&search_token=' . urlencode(html_entity_decode($_GET['search_token'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_state'])) {
            $url .= '&search_state=' . $_GET['search_state'];
        }

        $sort_route[] = [
            'nickname'    => url('Admin/User/index') . '?sort=nickname' . $url,
            'sex'         => url('Admin/User/index') . '?sort=sex' . $url,
            'phone'       => url('Admin/User/index') . '?sort=phone' . $url,
            'token'       => url('Admin/User/index') . '?sort=token' . $url,
            'create_date' => url('Admin/User/index') . '?sort=create_date' . $url,
            'state'       => url('Admin/User/index') . '?sort=state' . $url
        ];

        $pageSize = 10;

        $data = [
            'search_nickname' => $search_nickname,
            'search_phone'    => $search_phone,
            'search_token'    => $search_token,
            'search_state'    => $search_state,
            'sort'            => $sort
        ];

        $user = $this->getUserInfo($data);
        $usernum = $this->getUserNum($data);

        $item = array_splice($user,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $usernum, $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_nickname=' . $search_nickname . '&search_phone=' . $search_phone . '&search_token=' . $search_token . '&search_state=' . $search_state,
            'pageName' => 'page',
        ]);

        $userinfo = $paginator->toArray()['data'];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '会员管理'],
            ['text' => '用户管理']
        ];

        $states = [
            [
                'title' => '正常',
                'value' => 1
            ],
            [
                'title' => '禁用',
                'value' => 0
            ]
        ];

        return view('Admin.user.user', [
            'breadcrumbs'     => $breadcrumbs,
            'states'          => $states,
            'usernum'         => $usernum,
            'userinfo'        => $userinfo,
            'paginator'       => $paginator,
            'sort_route'      => $sort_route,
            'sort'            => $sort,
            'search_nickname' => $search_nickname,
            'search_phone'    => $search_phone,
            'search_token'    => $search_token,
            'search_state'    => $search_state
        ]);
    }

    // 删除用户
    public function delete($id){

        $userinfo = UserInfo::where('id', $id)->update(['is_state'=>'0']);

        if ($userinfo != 0) {
            return redirect('jump')->with(['message' => '删除成功！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '删除失败！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
        }

    }

    // 批量删除
    public function batchDelete(Request $request){
        $user_ids = $request->input('selected');

        if (!empty($user_ids)) {

            foreach ($user_ids as $user_id) {
                $nickname = UserInfo::find($user_id)->value('nickname');
                $userinfo = UserInfo::where('id', $user_id)->update(['is_state' => '0']);

                if ($userinfo == 0) {
                    $fail_account[] = $nickname;
                }
            }

            if(empty($fail_account)){
                return redirect('jump')->with(['message' => '批量删除成功！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
            }else{
                return redirect('jump')->with(['message' => implode(',', $fail_account) . '批量删除失败！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
            }

        } else {
            return redirect('jump')->with(['message' => '请先选择要删除的数据！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 用户状态停用或启用
    public function stopOrStart($id){
        // 1 正常 0 禁用
        $user = UserInfo::find($id);

        if ('0' === $user->state) {
            $user->state = '1';
        }else {
            $user->state = '0';
        }

        if ($user->save()) {
            return redirect('jump')->with(['message' => '修改成功！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '修改失败！', 'url' => '/Admin/User/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 查询用户列表数据
    public function getUserInfo($data = array()) {
        $sql = "SELECT id,nickname,sex,phone,state,create_date,asset_type,token FROM (SELECT id,nickname,sex,phone,state,bui.create_date,asset_type,is_state,(SELECT card_name FROM base_card_info WHERE card_id = bua.asset_type) as token FROM base_user_info AS bui LEFT JOIN bis_user_asset AS bua ON bui.id = bua.user_id GROUP BY bui.id) AS userinfo";

        $whereList = [];
        if (!empty($data['search_nickname'])) {
            $whereList[] = " nickname LIKE '%" . $data['search_nickname'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (!empty($data['search_token'])) {
            $whereList[] = " token LIKE '%" . $data['search_token'] . "%'";
        }

        if (isset($data['search_state']) && !is_null($data['search_state'])) {
            $whereList[] = " state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE ".implode(' AND', $whereList);
            $sql .= " AND is_state=1";
        } else {
            $sql .= " WHERE is_state=1";
        }

        $sql .=" GROUP BY id";

        $sort_data = [
            'nickname',
            'sex',
            'phone',
            'token',
            'create_date',
            'state'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $user = DB::select($sql);

        return $user;

    }

    // 数据数量
    public function getUserNum($data = array()){
        $sql = "SELECT COUNT(id) AS total FROM (SELECT id,nickname,sex,phone,state,bui.create_date,asset_type,is_state,(SELECT card_name FROM base_card_info WHERE card_id = bua.asset_type) as token FROM base_user_info AS bui LEFT JOIN bis_user_asset AS bua ON bui.id = bua.user_id GROUP BY bui.id) AS userinfo";

        $whereList = [];
        if (!empty($data['search_nickname'])) {
            $whereList[] = " nickname LIKE '%" . $data['search_nickname'] . "%'";
        }

        if (!empty($data['search_phone'])) {
            $whereList[] = " phone LIKE '%" . $data['search_phone'] . "%'";
        }

        if (!empty($data['search_token'])) {
            $whereList[] = " token LIKE '%" . $data['search_token'] . "%'";
        }

        if (isset($data['search_state']) && $data['search_state'] != '*') {
            $whereList[] = " state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE ".implode(' AND', $whereList);
            $sql .= " AND is_state=1";
        } else {
            $sql .= " WHERE is_state=1";
        }

        $sort_data = [
            'nickname',
            'sex',
            'phone',
            'token',
            'create_date',
            'state'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $reaults= DB::select($sql);
        foreach ($reaults as $result) {
            return $result->total;
        }
    }
}