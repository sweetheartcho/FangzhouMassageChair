@include('Admin.common.meta')
<body>
<div class="x-nav">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:left;padding-top:5px; margin-right:10px;" href="{{ url('Admin/Merchant/index') }}" title="返回上一页">
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
    <form class="layui-form" method="post" action="{{ url('Admin/Merchant/MerchantEdit') }}">
        {{ csrf_field() }}
        <input type="hidden" name="merchant_id" value="{{ $merchantinfo->merchant_id }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label">贵宾厅名称<span class="x-red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="Merchant[merchant_name]" autocomplete="off" value="{{ Session::has('merchant_name')?Session::pull('merchant_name'):$merchantinfo->merchant_name }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号<span class="x-red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="Merchant[merchant_phone]" autocomplete="off" value="{{ Session::has('merchant_phone')?Session::pull('merchant_phone'):$merchantinfo->merchant_phone }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">账号<span class="x-red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="Merchant[merchant_account]" autocomplete="off" value="{{ Session::has('merchant_account')?Session::pull('merchant_account'):$merchantinfo->merchant_account }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="password" name="Merchant[merchant_password]" autocomplete="off" value="{{ Session::has('merchant_password')?Session::pull('merchant_password'):'' }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <button class="layui-btn" id="modify-button" key="set-mine" lay-filter="save">保存</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['element','form','layer'], function(){
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        layer = layui.layer;//弹出层
        form = layui.form(); //表单
    });

    $('#modify-button').click(function () {
        var merchant_name = $("input[name='Merchant[merchant_name]']").val();
        var merchant_phone = $("input[name='Merchant[merchant_phone]']").val();
        var merchant_account = $("input[name='Merchant[merchant_account]']").val();

        var phone_pattern = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;

        if ('' == merchant_name) {
            layer.alert('贵宾厅名称不能为空');
            return false;
        }

        if ('' == merchant_phone) {
            layer.alert('联系方式不能为空');
            return false;
        } else if (!phone_pattern.test(merchant_phone)) {
            layer.alert('请填写正确格式的手机号');
            return false;
        }

        if ('' == merchant_account) {
            layer.alert('账户名不能为空');
            return false;
        }
    });
</script>
</body>
</html>