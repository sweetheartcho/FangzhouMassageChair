<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
{
    const STATE_NORMAL = 0;   // 正常
    const STATE_OUTDATE = 1;  // 已过期
    const STATE_STOP = 2;     // 后台停用

    protected $table = 'bis_user_asset';

    protected $primaryKey = 'asset_id';

    public $timestamps = false;

    // 处理购买记录状态
    public function handleState($state = null){
        $arr = [
            SELF::STATE_NORMAL  => '正常',
            SELF::STATE_OUTDATE => '已过期',
            SELF::STATE_STOP    => '后台停用'
        ];

        if (null != $state) {
            return array_key_exista($state, $arr) ? $arr[$state] : SELF::STATE_NORMAL;
        }
    }
}
