<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/*use Illuminate\Foundation\Auth\User as Authenticatable;*/

class UserInfo extends Model
{
    const SEX_GIRL = 0;  // 女
    const SEX_BOY = 1;   // 男
    const SEX_UN = 2;    // 未知
    const STATE_NORMAL = 1;   //正常
    const STATE_ABNORMAL = 0; //禁用

    protected $table = 'base_user_info';

    public $timestamps = false;

    protected function getDateFormat() {
        return time();
    }

    protected function asDateTime($val) {
        return $val;
    }

    // 处理性别
    public function sex($sex = null) {
        $arr = [
            SELF::SEX_GIRL => '女',
            SELF::SEX_BOY  => '男',
            SELF::SEX_UN   => '未知'
        ];

        if ($sex !== null) {
            return array_key_exists($sex, $arr) ? $arr[$sex] : $arr[SELF::SEX_UN];
        }

        return $arr;
    }

    // 处理用户状态
    public function user_state($state = null){
        $arr = [
            SELF::STATE_NORMAL   => '正常',
            SELF::STATE_ABNORMAL => '禁用'
        ];

        if ($state !== null){
            return array_key_exists($state, $arr) ? $arr[$state] : $arr[SELF::STATE_NORMAL];
        }

        return $arr;
    }
}
