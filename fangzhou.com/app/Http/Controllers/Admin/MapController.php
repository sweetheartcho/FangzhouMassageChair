<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

class MapController extends BaseController
{
    // 地图视图
    public function index() {
        return view('Admin.map.map');
    }
}
