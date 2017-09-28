@include('Admin.common.meta')
<body>
<div class="x-nav">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:left;padding-top:5px; margin-right:10px;" href="{{ url('Admin/Company/index') }}" title="返回上一页">
        <i class="layui-icon" style="font-size: 30px;">&lt;</i>
    </a>
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <form class="layui-form" id="edit-form" name="company" method="post" action="{{ url('Admin/Company/companyEdit') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="company_id" value="{{ $companyinfo->company_id }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label">商户名称<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_name]" autocomplete="off" value="{{ Session::has('company_name')?Session::pull('company_name'):$companyinfo->company_name }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商户缩写<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_abbreviation]" autocomplete="off" value="{{ Session::has('company_abbreviation')?Session::pull('company_abbreviation'):$companyinfo->company_abbreviation }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_phone]" autocomplete="off" value="{{ Session::has('company_phone')?Session::pull('company_phone'):$companyinfo->company_phone }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">贵宾厅</label>
            <div class="layui-input-inline-modify">
                <select name="Company[merchant_id]">
                    <option value="*">默认</option>
                    @foreach($merchantname as $merchant)
                        @if($companyinfo->merchant_id == $merchant['merchant_id']||(Session::has('merchant_id') && Session::get('merchant_id') == $merchant['merchant_id']))
                            <option value="{{ $merchant['merchant_id'] }}" selected>{{ $merchant['merchant_name'] }}</option>
                            {{ Session::forget('merchant_id') }}
                        @else
                            <option value="{{ $merchant['merchant_id'] }}">{{ $merchant['merchant_name'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">地址<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="button" class="layui-btn" id="add-address" value="添加坐标" style="width:100px;"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">经度</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_longitude]" value="{{ Session::has('company_longitude')?Session::pull('company_longitude'):$companyinfo->company_longitude }}" disabled class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">纬度</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_latitude]" value="{{ Session::has('company_latitude')?Session::pull('company_latitude'):$companyinfo->company_latitude }}" disabled class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">账号<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_account]" autocomplete="off" value="{{ Session::has('company_account')?Session::pull('company_account'):$companyinfo->company_account }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="password" name="Company[company_password]" autocomplete="off" value="{{ Session::has('company_password')?Session::pull('company_password'):'' }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">优惠金额</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_price]" autocomplete="off" value="{{ Session::has('company_price')?Session::pull('company_price'):$companyinfo->company_price }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_description]" autocomplete="off" value="{{ Session::has('company_description')?Session::pull('company_description'):$companyinfo->company_description }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结构组织代码</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Company[company_bar_code]" autocomplete="off" value="{{ Session::has('company_bar_code')?Session::pull('company_bar_code'):$companyinfo->company_bar_code }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">共使用时间</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="city" value="{{ $usetime }}" disabled="disabled" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务员</label>
            <div class="layui-input-block">
                <input type="button" value="查看服务员信息" class="layui-btn" onclick="waiterinfo();"/>
                <input type="button" value="添加服务员信息" class="layui-btn" onclick="addwaiter();"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">已上传图片</label>
            <div class="layui-input-inline" style="width:500px;">
                <div class="col-sm-12">
                    @foreach($companyphoto as $photo)
                        <div class="col-sm-3 img-thumbnail image-show-area">
                            <img src="{{ asset($photo->image) }}" alt="{{ $photo->image }}"/>
                            <a class="glyphicon glyphicon-remove" onclick="deleteImg('{{ $photo->photo_id }}');">删除</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">修改图片</label>
            <div class="layui-input-inline" style="width:500px;">
                <div class="col-sm-12">
                    <span class="glyphicon glyphicon-plus-sign" id="addImage"></span>
                </div>
                <input type="hidden" name="company_photo" value=""/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <button class="layui-btn" id="edit-button" key="set-mine" lay-filter="save">提交</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['element', 'layer', 'form'], function () {
        $ = layui.jquery;  // jquery
        lement = layui.element();  // 面包屑导航
        layer = layui.layer;  // 弹出层
        form = layui.form();  // 表单
    });

    $('#add-address').click(function () {
        layer.open({
            type: 2,
            title: '地址',
            area: ['1000px', '650px'],
            shadeClose: true,
            content: "{{ url('Admin/Map/index') }}"
        });
    });

    $('#addImage').click(function () {
        layer.open({
            type: 2,
            title: '选择图片',
            area: ['1000px', '650px'],
            shadeClose: true,
            content: "{{ url('Admin/Img/index') }}"
        });
    });

    function waiterinfo() {
        window.location.href = "{{ url('Admin/Waiter/index') }}/id/{{ $companyinfo->company_id }}";
    }

    function addwaiter() {
        window.location.href = "{{ url('Admin/Waiter/waiterAddIndex') }}/id/{{ $companyinfo->company_id }}";
    }
</script>
<script>
    $('#edit-button').click(function () {
        var company_name = $("input[name='Company[company_name]']").val();
        var company_abbreviation = $("input[name='Company[company_abbreviation]']").val();
        var company_phone = $("input[name='Company[company_phone]']").val();
        var company_longitude = $("input[name='Company[company_longitude]']").val();
        var company_latitude = $("input[name='Company[company_latitude]']").val();
        var company_account = $("input[name='Company[company_account]']").val();

        // 获取图片路径
        var image_src = [];
        $('.img-body img').each(function () {
            image_src.push($(this).attr('src'));
        });
        $("input[name='company_photo']").val(image_src);

        var phone_pattern = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;

        if ('' == company_name) {
            layer.alert('商户名称不能为空');
            return false;
        } else if ('' == company_abbreviation) {
            layer.alert('商户缩写不能为空');
            return false;
        } else if ('' == company_phone) {
            layer.alert('联系方式不能为空');
            return false;
        } else if (!phone_pattern.test(company_phone)) {
            layer.alert('手机号码格式错误');
            return false;
        } else if ('' == company_longitude) {
            layer.alert('地址不能为空');
            return false;
        } else if ('' == company_latitude) {
            layer.alert('地址不能为空');
            return false;
        } else if ('' == company_account) {
            layer.alert('账号不能为空');
            return false;
        }

        $("input[name='Company[company_longitude]']").removeAttr('disabled');
        $("input[name='Company[company_latitude]']").removeAttr('disabled');
    });
</script>
<script>
    function deleteImg(photoId) {
        $.ajax({
            type: 'POST',
            url: "{{ url('Admin/Company/deleteImg') }}",
            data: {id: photoId},
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function (msg) {
                layer.alert(msg, function () {
                    window.location.reload();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
</script>
</body>
</html>