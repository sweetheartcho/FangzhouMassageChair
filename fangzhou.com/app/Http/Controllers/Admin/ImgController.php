<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;

use Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ImgController extends BaseController
{
    // 图片页面
    public function index(){

        if (request()->has('page')) {
            $page = request()->input('page');
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        $pageSize = 4;

        $data = $this->getImg($page, $pageSize);
        $paginator = new LengthAwarePaginator($data['item'], $data['num'], $pageSize, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        $images = $paginator->toArray()['data'];

        return view('Admin.img.img', compact('images', 'paginator'));
    }

    // 获取图片
    public function getImg($page,$pageSize){
        $num = 0;
        $path = public_path('uploads');
        $handle = opendir($path);

        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                list($filename, $ext) = explode('.', $file);

                if ('jpg' == strtolower($ext) || 'png' == strtolower($ext) || 'gif' == strtolower($ext)) {
                    if (!is_dir('./' . $file)) {
                        $fileArr[] = [
                            'filename' => $file,
                            'filepath' => '/uploads/' . $file,
                            'type'     => 'image'
                        ];
                        $num++;
                    }
                }
            }

            $item = array_splice($fileArr, ($page - 1) * $pageSize, $pageSize);
            $data = [
                'item' => $item,
                'num' => $num
            ];

            return $data;
        }
    }

    // 上传图片
    public function upload() {
        if (request()->isMethod('POST')) {
            $images = request()->file('uploadimg');
            if (!empty($images)) {
                foreach ($images as $img) {
                    if ($this->checkImgType($img)) {
                        if ($img->isValid()) {
                            $ext = $img->getClientOriginalExtension();
                            $realpath = $img->getRealPath();

                            $newfilename[$realpath] = [
                                'newname'  => date('YmdHis') . '-' . uniqid() . '.' . $ext,
                                'original' => $img->getClientOriginalName()
                            ];
                        }
                    } else {
                        $filename[] = $img->getClientOriginalName();
                    }
                }

                if (!empty($filename)) {
                    return response()->json(implode(',', $filename) . '格式错误');
                } else {
                    foreach ($newfilename as $path => $name) {
                        $upload = Storage::disk('uploads')->put($name['newname'], file_get_contents($path));
                        if (!$upload) {
                            $failname[] = $name['original'];
                        }
                    }

                    if (empty($failname)) {
                        return response()->json('上传成功');
                    }else{
                        return response()->json(implode(',', $failname) . '上传失败');
                    }
                }
            }
        }
    }

    // 删除
    public function delete(){
        if(request()->isMethod('POST')){
            $images = request()->input('imgpath');

            foreach ($images as $img) {
                $delete = unlink(public_path() . $img);
                if (!$delete) {
                    $imgArr = explode('/', $img);
                    $imgname[] = $imgArr[count($imgArr)-1];
                }
            }

            if (empty($imgname)) {
                return response()->json('删除成功');
            } else {
                return response()->json(implode(',', $imgname) . '删除失败');
            }
        }
    }

    // 验证图片格式
    public function checkImgType($file){
        if (!empty($file)) {
            $filetypes = [
                'image/jpeg',
                'image/gif',
                'image/png'
            ];

            $type = $file->getMimeType();
            if (in_array($type, $filetypes)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
