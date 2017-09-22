<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class BaseController extends Controller
{
    // 转义标签
    public function handle_label($data) {
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $hadledata[$key] = $this->handle_label($val);
            } else {
                $hadledata[$key] = trim(htmlentities($val));
            }
        }

        return $hadledata;
    }
}
