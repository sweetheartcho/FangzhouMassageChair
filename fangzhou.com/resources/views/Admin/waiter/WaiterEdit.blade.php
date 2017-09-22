@include('Admin.common.meta')
<body>
<div class="x-nav">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:left;padding-top:5px; margin-right:10px;" href="{{ url('Admin/Waiter/index') }}/id/{{ $waiterinfo->company_id }}" title="返回上一页">
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
    <form class="layui-form" method="post" action="{{ url('Admin/Waiter/waiterEdit') }}">
        {{ csrf_field() }}
        <input type="hidden" name="waiter_id" value="{{ $waiterinfo->waiter_id }}"/>
        <input type="hidden" name="company_id" value="{{ $waiterinfo->company_id }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label">编号</label>
            <div class="layui-input-inline">
                <input type="text" name="Waiter[code]" value="{{ $waiterinfo->code }}" disabled="disabled" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">服务员姓名<span class="x-red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="Waiter[waiter_name]" autocomplete="off" value="{{ Session::has('waiter_name')?Session::pull('waiter_name'):$waiterinfo->waiter_name }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号<span class="x-red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" name="Waiter[waiter_telephone]" autocomplete="off" value="{{ Session::has('waiter_telephone')?Session::pull('waiter_telephone'):$waiterinfo->waiter_telephone }}" class="layui-input">
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
        var waiter_name = $("input[name='Waiter[waiter_name]']").val();
        var waiter_telephone = $("input[name='Waiter[waiter_telephone]']").val();

        var phone_pattern = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;

        if ('' == waiter_name) {
            layer.alert('服务员姓名不能为空');
            return false;
        }

        if ('' == waiter_telephone) {
            layer.alert('手机号不能为空');
            return false;
        } else if (!phone_pattern.test(waiter_telephone)) {
            layer.alert('请填写正确格式的手机号');
            return false;
        }
    });
</script>
</body>
</html>