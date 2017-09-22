<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use App\Model\UserCardOperation;
use DB;

class HomeController extends BaseController
{
    // 登陆后显示主页
    public function index(){
        return view('Admin.home.index');
    }

    // 主页内容
    public function home(){
        $latestCard = $this->latestCard();
        $outDateCard = $this->outDateCard();
        $unusualOrder = $this->unusualOrder();

        return view('Admin.home.home', [
            'latestCard'   => $latestCard,
            'outDateCard'  => $outDateCard,
            'unusualOrder' => $unusualOrder
        ]);
    }

    // 最近购买令牌
    public function latestCard(){
        $week = date('Y-m-d', strtotime('-1 week'));

        $total = UserCardOperation::where('operate_type', '1')->where('operate_date', '>=', $week)->count('id');
        return $total;
    }

    // 即将过期令牌
    public function outDateCard() {
        $week = date('Y-m-d', strtotime('+1 week'));
        $date = date('Y-m-d');

        $sql = "SELECT COUNT('id') AS total FROM (SELECT asset_deadline FROM bis_user_card_operation AS buco LEFT JOIN bis_user_asset AS bua 
               ON buco.user_id=bua.user_id WHERE operate_type=1 AND bua.asset_deadline >= '" . $week . "' AND bua.asset_deadline<='" . $date . "'
               GROUP BY buco.id) as cardinfo;";
        $total = DB::select($sql);

        foreach ($total as $value) {
            return $value->total;
        }
    }

    // 异常订单
    public function unusualOrder(){
        $week = date('Y-m-d', strtotime('-1 week'));

        $total = UserCardOperation::where('is_state', '=', '1')->where('operate_date', '>=', $week)->count('id');
        return $total;
    }
}
