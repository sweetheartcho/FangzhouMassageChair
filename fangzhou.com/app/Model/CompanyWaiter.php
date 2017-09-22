<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyWaiter extends Model
{
    protected $table = 'base_company_waiter';

    protected $primaryKey = 'waiter_id';

    protected $fillable = ['company_id', 'code', 'waiter_name', 'waiter_telephone','create_date'];

    public $timestamps = false;
}
