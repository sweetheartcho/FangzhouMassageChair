<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use DB;

class RecordController extends BaseController
{
    // 购买记录页面
    public function buyIndex(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'nickname';
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        if (isset($_GET['search_recommender_code'])) {
            $search_recommender_code = $_GET['search_recommender_code'];
        } else {
            $search_recommender_code = '';
        }

        if (isset($_GET['search_card_name'])) {
            $search_card_name = $_GET['search_card_name'];
        } else {
            $search_card_name = '';
        }

        if (isset($_GET['search_company_name'])) {
            $search_company_name = $_GET['search_company_name'];
        } else {
            $search_company_name = '';
        }

        if (isset($_GET['search_state'])) {
            $search_state = $_GET['search_state'];
        } else {
            $search_state = '-1';
        }

        $url = '';
        if (isset($_GET['search_recommender_code'])) {
            $url .= '&search_recommender_code=' . urlencode(html_entity_decode($_GET['search_recommender_code'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_card_name'])) {
            $url .= '&search_card_name=' . urlencode(html_entity_decode($_GET['search_card_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_company_name'])) {
            $url .= '&search_company_name=' . urlencode(html_entity_decode($_GET['search_company_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_state'])) {
            $url .= '&search_state=' . $_GET['search_state'];
        }

        $sort_route[] = [
            'nickname'            => url('Admin/Record/buyIndex') . '?sort=nickname' . $url,
            'user_telephone'      => url('Admin/Record/buyIndex') . '?sort=user_telephone' . $url,
            'recommender_code'    => url('Admin/Record/buyIndex') . '?sort=recommender_code' . $url,
            'card_name'           => url('Admin/Record/buyIndex') . '?sort=card_name' . $url,
            'mark1'               => url('Admin/Record/buyIndex') . '?sort=mark1' . $url,
            'company_name'        => url('Admin/Record/buyIndex') . '?sort=company_name' . $url,
            'operate_description' => url('Admin/Record/buyIndex') . '?sort=operate_description' . $url,
            'asset_state'         => url('Admin/Record/buyIndex') . '?sort=asset_state' . $url,
            'create_date'         => url('Admin/Record/buyIndex') . '?sort=create_date' . $url,
            'asset_deadline'      => url('Admin/Record/buyIndex') . '?sort=asset_deadline' . $url
        ];

        $data = [
            'sort'                    => $sort,
            'search_recommender_code' => $search_recommender_code,
            'search_card_name'        => $search_card_name,
            'search_company_name'     => $search_company_name,
            'search_state'            => $search_state
        ];

        $pageSize = 10;

        $buy = $this->getBuyRecord($data);
        $buyrecordNum = $this->getBuyRecordNum($data);

        $item = array_splice($buy,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $buyrecordNum, $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_recommender_code=' . $search_recommender_code . '&search_card_name=' . $search_card_name . '&search_company_name=' . $search_company_name . '&search_state=' . $search_state,
            'pageName' => 'page',
        ]);

        $buyrecord = $paginator->toArray()['data'];

        $states = [
            [
                'value' => '-1',
                'title' => '请选择状态'
            ],
            [
                'value' => 0,
                'title' => '正常'
            ],
            [
                'value' => 1,
                'title' => '已过期'
            ],
            [
                'value' => 2,
                'title' => '后台停用'
            ]
        ];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '操作记录'],
            ['text' => '购买记录']
        ];

        return view('Admin.record.buy', [
            'breadcrumbs'             => $breadcrumbs,
            'buyrecord'               => $buyrecord,
            'buyrecordNum'            => $buyrecordNum,
            'paginator'               => $paginator,
            'states'                  => $states,
            'sort_route'              => $sort_route,
            'sort'                    => $sort,
            'search_recommender_code' => $search_recommender_code,
            'search_card_name'        => $search_card_name,
            'search_company_name'     => $search_company_name,
            'search_state'            => $search_state
        ]);
    }

    // 查询购买记录
    public function getBuyRecord($data = array()){
        $sql = "SELECT bci.company_name,bci2.card_name,bui.nickname,bua.user_id,recommender_code,asset_type,asset_deadline,asset_state,
                    bua.create_date,user_telephone,buco.company_id,operate_type,operate_description,mark1 
                    FROM bis_user_asset AS bua 
                    LEFT JOIN bis_user_card_operation AS buco ON bua.user_id = buco.user_id 
                    LEFT JOIN base_company_info AS bci ON bci.company_id = buco.company_id 
                    LEFT JOIN base_card_info AS bci2 ON bci2.card_id = bua.asset_type 
                    LEFT JOIN base_user_info AS bui ON bui.id = buco.user_id";

        $whereList = [];
        if (!empty($data['search_recommender_code'])) {
            $whereList[] = " recommender_code LIKE '%" . $data['search_recommender_code'] . "%'";
        }

        if (!empty($data['search_card_name'])) {
            $whereList[] = " bci2.card_name LIKE '%" . $data['search_card_name'] . "%'";
        }

        if (!empty($data['search_company_name'])) {
            $whereList[] = " bci.company_name LIKE '%" . $data['search_company_name'] . "%'";
        }

        if (isset($data['search_state']) && !is_null($data['search_state']) && $data['search_state'] != '-1') {
            $whereList[] = " asset_state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
            $sql .=" AND operate_type=1";
        } else {
            $sql .=" WHERE operate_type=1";
        }

        $sql .= " GROUP BY bua.asset_id";

        $sort_data = [
            'nickname',
            'user_telephone',
            'recommender_code',
            'card_name',
            'mark1',
            'company_name',
            'operate_description',
            'asset_state',
            'create_date',
            'asset_deadline'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $buyrecord = DB::select($sql);
        return $buyrecord;

    }

    // 数据数量
    public function getBuyRecordNum($data = array()){
        $sql = "SELECT COUNT('asset_id') AS total FROM (SELECT bci.company_name,bci2.card_name,bui.nickname,bua.user_id,recommender_code,asset_type,asset_deadline,asset_state,
                bua.create_date,user_telephone,buco.company_id,operate_type,operate_description,mark1
                FROM bis_user_asset AS bua 
                LEFT JOIN bis_user_card_operation AS buco ON bua.user_id = buco.user_id 
                LEFT JOIN base_company_info AS bci ON bci.company_id = buco.company_id 
                LEFT JOIN base_card_info AS bci2 ON bci2.card_id = bua.asset_type 
                LEFT JOIN base_user_info AS bui ON bui.id = buco.user_id
                WHERE operate_type=1 GROUP BY bua.asset_id) AS buyinfo";

        $whereList = [];
        if (!empty($data['search_recommender_code'])) {
            $whereList[] = " recommender_code LIKE '%" . $data['search_recommender_code'] . "%'";
        }

        if (!empty($data['search_card_name'])) {
            $whereList[] = " card_name LIKE '%" . $data['search_card_name'] . "%'";
        }

        if (!empty($data['search_company_name'])) {
            $whereList[] = " company_name LIKE '%" . $data['search_company_name'] . "%'";
        }

        if (isset($data['search_state']) && !is_null($data['search_state']) && $data['search_state'] != '-1') {
            $whereList[] = " asset_state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
        }

        $sort_data = [
            'nickname',
            'user_telephone',
            'recommender_code',
            'card_name',
            'mark1',
            'company_name',
            'operate_description',
            'asset_state',
            'create_date',
            'asset_deadline'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $results = DB::select($sql);
        foreach ($results as $result) {
            return $result->total;
        }
    }

    // 使用记录页面
    public function employIndex(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'nickname';
        }

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        if (isset($_GET['search_mark1'])) {
            $search_mark1 = $_GET['search_mark1'];
        } else {
            $search_mark1 = '';
        }

        if (isset($_GET['search_card_name'])) {
            $search_card_name = $_GET['search_card_name'];
        } else {
            $search_card_name = '';
        }

        if (isset($_GET['search_company_name'])) {
            $search_company_name = $_GET['search_company_name'];
        } else {
            $search_company_name = '';
        }

        if (isset($_GET['search_state'])) {
            $search_state = $_GET['search_state'];
        } else {
            $search_state = '-1';
        }

        $url = '';
        if (isset($_GET['search_mark1'])) {
            $url .= '&search_mark1=' . urlencode(html_entity_decode($_GET['search_mark1'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_card_name'])) {
            $url .= '&search_card_name=' . urlencode(html_entity_decode($_GET['search_card_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_company_name'])) {
            $url .= '&search_company_name=' . urlencode(html_entity_decode($_GET['search_company_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($_GET['search_state'])) {
            $url .= '&search_state=' . $_GET['search_state'];
        }

        $sort_route[] = [
            'nickname'            => url('Admin/Record/employIndex') . '?sort=nickname' . $url,
            'user_telephone'      => url('Admin/Record/employIndex') . '?sort=user_telephone' . $url,
            'mark1'               => url('Admin/Record/employIndex') . '?sort=mark1' . $url,
            'card_name'           => url('Admin/Record/employIndex') . '?sort=card_name' . $url,
            'company_name'        => url('Admin/Record/employIndex') . '?sort=company_name' . $url,
            'use_time'            => url('Admin/Record/employIndex') . '?sort=use_time' . $url,
            'mark2'               => url('Admin/Record/employIndex') . '?sort=mark2' . $url,
            'operate_description' => url('Admin/Record/employIndex') . '?sort=operate_description' . $url,
            'operate_date'        => url('Admin/Record/employIndex') . '?sort=operate_date' . $url,
            'is_state'            => url('Admin/Record/employIndex') . '?sort=is_state' . $url,
        ];

        $data = [
            'sort'                 => $sort,
            'search_mark1'         => $search_mark1,
            'search_card_name'     => $search_card_name,
            'search_company_name'  => $search_company_name,
            'search_state'         => $search_state
        ];

        $pageSize = 10;

        $employ = $this->getEmployRecord($data);
        $employrecordNum = $this->getEmployRecordNum($data);

        $item = array_splice($employ,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $employrecordNum, $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_mark1=' . $search_mark1 . '&search_card_name=' . $search_card_name . '&search_company_name=' . $search_company_name . '&search_state=' . $search_state,
            'pageName' => 'page',
        ]);

        $employrecord = $paginator->toArray()['data'];

        $states = [
            [
                'value' => '-1',
                'title' => '请选择状态'
            ],
            [
                'value' => 0,
                'title' => '正常'
            ],
            [
                'value' => 1,
                'title' => '超限'
            ]
        ];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '操作记录'],
            ['text' => '使用记录']
        ];

        return view('Admin.record.employ', [
            'breadcrumbs'          => $breadcrumbs,
            'employrecord'         => $employrecord,
            'employrecordNum'      => $employrecordNum,
            'paginator'            => $paginator,
            'sort_route'           => $sort_route,
            'sort'                 => $sort,
            'search_mark1'         => $search_mark1,
            'search_card_name'     => $search_card_name,
            'search_company_name'  => $search_company_name,
            'search_state'         => $search_state,
            'states'               => $states
        ]);
    }

    // 查询使用记录
    public function getEmployRecord($data = array()){
        $sql = "SELECT company_name,card_name,nickname,buco.user_id,buco.company_id,use_time,operate_type,operate_description,operate_date,
                  buco.is_state,mark1,mark2,user_telephone 
                  FROM bis_user_card_operation AS buco 
                  LEFT JOIN bis_user_asset AS bua ON bua.user_id = buco.user_id 
                  LEFT JOIN base_company_info AS bci ON bci.company_id = buco.company_id 
                  LEFT JOIN base_card_info AS bci2 ON bci2.card_id = bua.asset_type 
                  LEFT JOIN base_user_info AS bui ON bui.id = buco.user_id";

        $whereList = [];
        if (!empty($data['search_mark1'])) {
            $whereList[] = " mark1 LIKE '%" . $data['search_mark1'] . "%'";
        }

        if (!empty($data['search_card_name'])) {
            $whereList[] = " card_name LIKE '%" . $data['search_card_name'] . "%'";
        }

        if (!empty($data['search_company_name'])) {
            $whereList[] = " company_name LIKE '%" . $data['search_company_name'] . "%'";
        }

        if (isset($data['search_state'])&& !is_null($data['search_state']) && $data['search_state'] != '-1') {
            $whereList[] = " buco.is_state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
            $sql .= " AND operate_type=2";
        } else {
            $sql .= " WHERE operate_type=2";
        }

        $sql .= " GROUP BY buco.id";

        $sort_data = [
            'company_name',
            'card_name',
            'nickname',
            'use_time',
            'operate_description',
            'operate_date',
            'is_state',
            'mark1',
            'mark2',
            'user_telephone'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $employ = DB::select($sql);
        return $employ;
    }

    // 数据数量
    public function getEmployRecordNum($data = array()){

        $sql = "SELECT COUNT(*) AS total FROM (SELECT company_name,card_name,nickname,buco.user_id,buco.company_id,use_time,operate_type,operate_description,operate_date,
                  buco.is_state,mark1,mark2,user_telephone 
                  FROM bis_user_card_operation AS buco 
                  LEFT JOIN bis_user_asset AS bua ON bua.user_id = buco.user_id 
                  LEFT JOIN base_company_info AS bci ON bci.company_id = buco.company_id 
                  LEFT JOIN base_card_info AS bci2 ON bci2.card_id = bua.asset_type 
                  LEFT JOIN base_user_info AS bui ON bui.id = buco.user_id WHERE operate_type=2 GROUP BY buco.id) AS employinfo";

        $whereList = [];
        if (!empty($data['search_mark1'])) {
            $whereList[] = " mark1 LIKE '%" . $data['search_mark1'] . "%'";
        }

        if (!empty($data['search_card_name'])) {
            $whereList[] = " card_name LIKE '%" . $data['search_card_name'] . "%'";
        }

        if (!empty($data['search_company_name'])) {
            $whereList[] = " company_name LIKE '%" . $data['search_company_name'] . "%'";
        }

        if (isset($data['search_state'])&& !is_null($data['search_state']) && $data['search_state'] != '-1') {
            $whereList[] = " is_state LIKE '%" . $data['search_state'] . "%'";
        }

        if (count($whereList) > 0) {
            $sql .= " WHERE" . implode(' AND', $whereList);
        }

        $sort_data = [
            'company_name',
            'card_name',
            'nickname',
            'use_time',
            'operate_description',
            'operate_date',
            'is_state',
            'mark1',
            'mark2',
            'user_telephone'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY nickname DESC";
        }

        $results = DB::select($sql);
        foreach ($results as $result) {
            return $result->total;
        }
    }
}