<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use App\Model\Config;

class ConfigController extends BaseController
{
    // 设定页面
    public function index(){

        $configimg = Config::where('type', '1')->get();

        $breadcrumbs = [
            ['text' => '首页'],
            ['text' => '系统设置'],
            ['text' => '基本设定']
        ];

        return view('Admin.config.config', [
            'breadcrumbs' => $breadcrumbs,
            'configimg'   => $configimg
        ]);
    }

    // 修改图片
    public function editimg(){
        if (request()->isMethod('POST')) {
            $image = request()->input('card_photo');

            if ($image != '') {
                $images = explode(',', $image);

                $fialimage = [];
                foreach ($images as $img) {
                    $imagepath = 'uploads/' . basename($img);
                    $results = Config::create(['card_photo' => $imagepath, 'type' => '1']);

                    if (empty($results)) {
                        $fialimage[] = basename($img);
                    }
                }

                if (empty($fialimage)) {
                    return redirect('jump')->with(['message' => '修改成功！', 'url' => 'Admin/Config/index', 'jumpTime' => 3, 'status' => false]);
                } else {
                    return redirect('jump')->with(['message' => implode(',', $fialimage) . '图片修改失败！', 'url' => 'Admin/Config/index', 'jumpTime' => 3, 'status' => false]);
                }
            } else {
                return redirect('jump')->with(['message' => '请注意：未添加图片！', 'url' => 'Admin/Config/index', 'jumpTime' => 3, 'status' => false]);
            }
        }
    }

    // 删除图片
    public function deleteImg(){
        if (request()->isMethod('POST')) {
            $id = request()->input('id');
            $photo = Config::where('config_Id', $id)->delete();

            if (0 == $photo) {
                return response()->json('删除失败');
            } else {
                return response()->json('删除成功');
            }
        }
    }
}
