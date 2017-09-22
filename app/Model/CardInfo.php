<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    protected $table = 'base_card_info';

    protected $primaryKey = 'card_id';

    public $timestamps = false;
}
