<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'base_merchant';

    protected $primaryKey = 'merchant_id';

    protected $fillable = ['merchant_name', 'merchant_phone', 'merchant_account', 'merchant_password', 'create_user_id'];

    public $timestamps = false;
}
