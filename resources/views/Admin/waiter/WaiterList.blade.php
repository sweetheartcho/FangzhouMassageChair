@include('Admin.common.meta')
<body>
<div class="x-nav">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:left;padding-top:5px; margin-right:10px;" href="{{ url('Admin/Company/companyEditIndex') }}/id/{{ $id }}" title="返回上一页">
        <i class="layui-icon">&lt;</i>
    </a>
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"  href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-form x-center layui-form-pane search_form" style="margin-top: 15px;">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="search_code" value="{{ $search_code }}" placeholder="请输入服务员编号" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_waiter_name" value="{{ $search_waiter_name }}" placeholder="请输入姓名" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline" style="width:80px">
                <button class="layui-btn" id="search-button" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="batchDelete()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：{{ $waiternum }} 条</span>
    </xblock>
    <form method="post" action="{{ url('Admin/Waiter/batchDelete') }}" id="waiter-form">
        {{ csrf_field() }}
    <table class="layui-table">
        <thead>
        <tr>
            <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);"></th>
            @foreach($sort_route as $route)
                @if('code' == $sort)
                    <th><a href="{{ $route['code'] }}">服务员编号</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['code'] }}">服务员编号</a></th>
                @endif
                @if('waiter_name' == $sort)
                    <th><a href="{{ $route['waiter_name'] }}">姓名</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['waiter_name'] }}">姓名</a></th>
                @endif
                @if('waiter_telephone' == $sort)
                    <th><a href="{{ $route['waiter_telephone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['waiter_telephone'] }}">联系方式</a></th>
                @endif
            @endforeach
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @if(empty($waiterinfo))
            <tr><td class="text-center" colspan="6">没有符合条件的结果！</td></tr>
        @else
            @foreach($waiterinfo as $waiter)
                <input type="hidden" name="company_id" value="{{ $waiter->company_id }}"/>
                <tr>
                    <td><input type="checkbox" value="{{ $waiter->waiter_id }}" name="selected[]"></td>
                    <td>{{ $waiter->code }}</td>
                    <td>{{ $waiter->waiter_name }}</td>
                    <td>{{ $waiter->waiter_telephone }}</td>
                    <td class="td-manage">
                        <a title="编辑" href="javascript:;" onclick="waiterEdit({{ $waiter->waiter_id }})"
                           class="ml-5" style="text-decoration:none">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="del({{ $waiter->waiter_id }});"
                           style="text-decoration:none">
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
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['laydate','element','laypage','layer'], function(){
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        laypage = layui.laypage;//分页
        layer = layui.layer;//弹出层
    });

    function del(id){
        layer.confirm('确认要删除吗？', function () {
            window.location.href = "{{ url('Admin/Waiter/delete') }}/id/" + id;
        }, function () {
            layer.close();
        });
    }

    function batchDelete() {
        var checkbox = $("input[type='checkbox']").is(':checked');

        if (false === checkbox) {
            layer.alert('请先选择要删除的数据');
        } else {
            layer.confirm('确认要删除吗？', function () {
                $('#waiter-form').submit();
            }, function () {
                layer.close();
            });
        }
    }

    function waiterEdit(id) {
        window.location.href = "{{ url('Admin/Waiter/waiterEditIndex') }}/id/" + id;
    }
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/Waiter/index') }}/id/" + "{{ $id }}";

        var search_code = $('input[name=\'search_code\']').val();
        if (search_code) {
            url += '?search_code=' + encodeURIComponent(search_code);
        }

        var search_waiter_name = $('input[name=\'search_waiter_name\']').val();
        if (search_waiter_name) {
            url += '?search_waiter_name=' + encodeURIComponent(search_waiter_name);
        }

        location = url;
    });
</script>
</body>
</html>