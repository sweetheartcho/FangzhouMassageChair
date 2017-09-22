@include('Admin.common.meta')
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-form x-center layui-form-pane search-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="search_name"  placeholder="请输入贵宾厅名称" value="{{ $search_name }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_phone"  placeholder="请输入联系方式" value="{{ $search_phone }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline" style="width:80px">
                <button class="layui-btn" id="search-button" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="batchDelete()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：{{ $merchantnum }} 条</span>
    </xblock>
    <form method="post" action="{{ url('Admin/Merchant/batchDelete') }}" id="merchant-form">
        {{ csrf_field() }}
    <table class="layui-table">
        <thead>
        <tr>
            <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);"></th>
            @foreach($sort_route as $route)
                @if('merchant_name' == $sort)
                    <th><a href="{{ $route['merchant_name'] }}">贵宾厅名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['merchant_name'] }}">贵宾厅名称</a></th>
                @endif
                @if('merchant_phone' == $sort)
                    <th><a href="{{ $route['merchant_phone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['merchant_phone'] }}">联系方式</a></th>
                @endif
                @if('merchant_account' == $sort)
                    <th><a href="{{ $route['merchant_account'] }}">账户名</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['merchant_account'] }}">账户名</a></th>
                @endif
                @if('create_date' == $sort)
                    <th><a href="{{ $route['merchant_create_date'] }}">添加时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['merchant_create_date'] }}">添加时间</a></th>
                @endif
            @endforeach
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @if(empty($merchantinfo))
            <tr><td class="text-center" colspan="6">没有符合条件的结果！</td></tr>
        @else
            @foreach($merchantinfo as $merchant)
                <tr>
                    <td><input type="checkbox" value="{{ $merchant->merchant_id }}" name="selected[]"></td>
                    <td>{{ $merchant->merchant_name }}</td>
                    <td>{{ $merchant->merchant_phone }}</td>
                    <td>{{ $merchant->merchant_account }}</td>
                    <td>{{ $merchant->create_date }}</td>
                    <td class="td-manage">
                        <a title="编辑" href="javascript:;" onclick="merchantEdit({{ $merchant->merchant_id }});" class="ml-5" style="text-decoration:none">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="del({{ $merchant->merchant_id }})" style="text-decoration:none">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    </form>
    <div class="pull-right">{!! $paginator->render() !!}</div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['element','laypage','layer'], function(){
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        laypage = layui.laypage;//分页
        layer = layui.layer;//弹出层
    });

    function batchDelete() {
        var checkbox = $("input[type='checkbox']").is(':checked');

        if (false === checkbox) {
            layer.alert('请先选择要删除的数据');
        }else{
            layer.confirm('确认要删除吗？', function () {
                $('#merchant-form').submit();
            }, function () {
                layer.close();
            });
        }
    }

    function del(id){
        layer.confirm('确认要删除吗？', function () {
            window.location.href = "{{ url('Admin/Merchant/delete') }}/id/" + id;
        }, function () {
            layer.close();
        });
    }

    function merchantEdit(merchant_id) {
        window.location.href = '{{ url('Admin/Merchant/merchantEditIndex/id') }}/' + merchant_id;
    }
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/Merchant/index') }}";

        var search_name = $('input[name=\'search_name\']').val();
        if (search_name) {
            url += '?search_name=' + encodeURIComponent(search_name);
        }

        var search_phone = $('input[name=\'search_phone\']').val();
        if (search_phone) {
            url += '?search_phone=' + encodeURIComponent(search_phone);
        }

        location = url;
    });
</script>
</body>
</html>