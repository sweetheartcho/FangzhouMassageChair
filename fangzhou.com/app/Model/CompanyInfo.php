<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $table = 'base_company_info';

    protected $primaryKey = 'company_id';  // 若数据表主键不是id,则需要指定

    protected $fillable = [
        'company_name',
        'company_abbreviation',
        'company_phone',
        'merchant_id',
        'company_longitude',
        'company_latitude',
        'company_account',
        'company_password',
        'company_description',
        'company_bar_code',
        'create_date'
    ];

    public $timestamps = false;

    protected function getDateFormat() {
        return time();
    }

    protected function asDateTime($val) {
        return $val;
    }
}
