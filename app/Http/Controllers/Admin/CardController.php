<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\CardInfo;
use DB;

class CardController extends BaseController
{
    // 商品管理页面
    public function index(){

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'card_name';
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

        $url = '';
        if (isset($_GET['search_name'])) {
            $url .= '&search_name=' . urlencode(html_entity_decode($_GET['search_name'], ENT_QUOTES, 'UTF-8'));
        }

        $sort_route[] = [
            'card_name'        => url('Admin/Card/index') . '?sort=card_name' . $url,
            'card_price'       => url('Admin/Card/index') . '?sort=card_price' . $url
        ];

        $data = [
            'sort'        => $sort,
            'search_name' => $search_name
        ];

        $card = $this->getProductInfo($data);
        $cardnum = $this->getProductNum($data);

        $pageSize = 10;

        $item = array_splice($card,($page - 1) * $pageSize, $pageSize);
        $paginator = new LengthAwarePaginator($item, $cardnum, $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath() . '?sort=' . $sort . '&search_name=' . $search_name,
            'pageName' => 'page',
        ]);

        $cardinfo = $paginator->toArray()['data'];

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '商品管理'],
            ['text' => '商品列表']
        ];

        return view('Admin.card.cardinfo', [
            'breadcrumbs' => $breadcrumbs,
            'cardinfo'    => $cardinfo,
            'cardnum'     => $cardnum,
            'paginator'   => $paginator,
            'sort_route'  => $sort_route,
            'sort'        => $sort,
            'search_name' => $search_name
        ]);
    }

    // 批量删除
    public function batchDelete(){
        if (request()->isMethod('POST')) {
            $cards_id = request()->input('selected');

            if (!empty($cards_id)) {
                $fail_cardname = [];

                foreach ($cards_id as $card_id) {
                    $cardname = CardInfo::where('card_id', $card_id)->value('card_name');
                    $cardinfo = CardInfo::where('card_id', $card_id)->update(['card_state' => '1']);

                    if ($cardinfo == 0) {
                        $fail_cardname[] = $cardname;
                    }
                }

                if (empty($fail_cardname)) {
                    return redirect('jump')->with(['message' => '批量删除成功!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
                } else {
                    return redirect('jump')->with(['message' => implode(',', $fail_cardname) . '删除失败!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
                }
            } else {
                return redirect('jump')->with(['message' => '请先选择要删除的数据！', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
            }

        }
    }

    // 删除
    public function delete($card_id){
        $cardinfo = CardInfo::where('card_id', "$card_id")->update(['card_state' => '1']);

        if ($cardinfo != 0) {
            return redirect('jump')->with(['message' => '删除成功!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '删除失败!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
        }
    }

    // 商品上下架
    public function stopOrStart($card_id){
        // 0 正常 1 下架
        $cardinfo = CardInfo::find($card_id);

        if ('0' === $cardinfo->card_state) {
            $cardinfo->card_state = '1';
        } else {
            $cardinfo->card_state = '0';
        }

        if ($cardinfo->save()) {
            return redirect('jump')->with(['message' => '修改成功！', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
        } else {
            return redirect('jump')->with(['message' => '修改失败！', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
        }

        //dd($cardinfo);
    }

    // 查询产品列表信息
    public function getProductInfo($data = array()){

        $sql = "SELECT * FROM base_card_info";

        if (!empty($data['search_name'])) {
            $sql .= " WHERE card_name LIKE '%" . $data['search_name'] . "%'";
        }

        $sql .= " GROUP BY card_id";

        $sort_data = [
            'card_name',
            'card_price'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        }else{
            $sql .= " ORDER BY card_name DESC";
        }

        $card = DB::select($sql);
        return $card;
    }

    // 数据数量
    public function getProductNum($data = array()){
        $sql = "SELECT COUNT(card_id) AS total FROM base_card_info";

        if (!empty($data['search_name'])) {
            $sql .= " WHERE card_name LIKE '%" . $data['search_name'] . "%'";
        }

        $sort_data = [
            'card_name',
            'card_price'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'] . " DESC";
        }else{
            $sql .= " ORDER BY card_name DESC";
        }

        $results = DB::select($sql);
        foreach($results as $result){
            return $result->total;
        }
    }

    // 商品编辑页面
    public function cardEditIndex($card_id) {

        $cardinfo = CardInfo::find($card_id);

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '商品管理'],
            ['text' => '商品列表'],
            ['text' => '修改商品信息']
        ];

        return view('Admin.card.CardEdit', [
            'cardinfo'    => $cardinfo,
            'card_id'     => $card_id,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // 保存编辑信息
    public function CardEdit() {
        if (request()->isMethod('POST')) {
            $card = $this->handle_label(request()->input('Card'));
            $card_id = request()->input('card_id');

            // 数据保留
            $this->saveSession($card);

            if ('' == $card['card_name'] || '' == $card['card_price']) {
                return redirect('jump')->with(['message' => '产品名称，价格不能为空!', 'url' => '/Admin/Card/cardEditIndex/card_id/' . $card_id, 'jumpTime' => 3, 'status' => false]);
            } else {

                $cardmessage = CardInfo::where('card_id', $card_id)->get(['card_name', 'card_price', 'card_description', 'card_logo']);
                foreach ($cardmessage as $value) {
                    $cardArr = $value;
                }

                if ($cardArr['card_name'] == $card['card_name'] && $cardArr['card_price'] == $card['card_price'] && $cardArr['card_description'] == $card['card_description'] && '' == $card['card_logo']) {
                    $this->forgetSession();
                    return redirect('jump')->with(['message' => '请注意：未修改任何信息!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
                }else{
                    if (isset($card['card_logo']) && $card['card_logo'] != '') {
                        $card['card_logo'] = 'uploads/' . basename($card['card_logo']);
                        $cardinfo = CardInfo::where('card_id', $card_id)->update($card);
                    } else {
                        unset($card['card_logo']);
                        $cardinfo = CardInfo::where('card_id', $card_id)->update($card);
                    }

                    if ($cardinfo == 0) {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改失败!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
                    } else {
                        $this->forgetSession();
                        return redirect('jump')->with(['message' => '修改成功!', 'url' => '/Admin/Card/index', 'jumpTime' => 3, 'status' => false]);
                    }
                }
            }
        }
    }

    // 将表单信息保存至session实现数据保留
    public function saveSession($data){
        if (!empty($data)) {
            request()->session()->put('card_name', $data['card_name']);
            request()->session()->put('card_price', $data['card_price']);
            request()->session()->put('card_description', $data['card_description']);
        }
    }

    // 操作成功清除部分session
    public function forgetSession(){
        request()->session()->forget('card_name');
        request()->session()->forget('card_price');
        request()->session()->forget('card_description');
    }
}
