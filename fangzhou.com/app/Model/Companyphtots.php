<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Companyphtots extends Model
{
    protected $table = 'base_company_photos';

    protected $primaryKey = 'photo_id';  // 若数据表主键不是id,则需要指定

    protected $fillable = [
        'company_id',
        'image',
        'create_date'
    ];

    public $timestamps = false;
}
