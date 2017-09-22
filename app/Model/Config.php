<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'base_config';

    protected $primaryKey = 'config_ID';

    protected $fillable = ['card_photo'];

    public $timestamps = false;
}
