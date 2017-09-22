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
                <input type="text" name="search_name" placeholder="请输入商户名称" value="{{ $search_name }}" autocomplete="off" class="layui-input" id="company-name">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_phone" placeholder="请输入联系方式" value="{{ $search_phone }}" autocomplete="off" class="layui-input" id="company-phone">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_abbreviation" placeholder="请输入简称" value="{{ $search_abbreviation }}" autocomplete="off" class="layui-input" id="company-abbreviation">
            </div>
            <div class="layui-input-inline" style="width:80px">
                <button class="layui-btn" id="search-button" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="batchDelete()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：{{ $companynum }} 条</span>
    </xblock>
    <form method="post" action="{{ url('Admin/Company/batchDelete') }}" id="company_form">
        {{ csrf_field() }}
        <table class="layui-table">
        <thead>
        <tr>
            <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);"></th>
            @foreach($sort_route as $route)
                @if('company_name' == $sort)
                    <th><a href="{{ $route['company_name'] }}">商户名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_name'] }}">商户名称</a></th>
                @endif
                @if('company_abbreviation' == $sort)
                    <th><a href="{{ $route['company_abbreviation'] }}">简称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_abbreviation'] }}">简称</a></th>
                @endif
                @if('company_phone' == $sort)
                    <th><a href="{{ $route['company_phone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_phone'] }}">联系方式</a></th>
                @endif
                @if('company_longitude' == $sort)
                    <th><a href="{{ $route['company_longitude'] }}">经度</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_longitude'] }}">经度</a></th>
                @endif
                @if('company_latitude' == $sort)
                    <th><a href="{{ $route['company_latitude'] }}">纬度</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_latitude'] }}">纬度</a></th>
                @endif
                @if('create_date' == $sort)
                    <th><a href="{{ $route['company_create_date'] }}">添加时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_create_date'] }}">添加时间</a></th>
                @endif
            @endforeach
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @if(empty($companyinfo))
            <tr><td class="text-center" colspan="8">没有符合条件的结果！</td></tr>
        @else
            @foreach($companyinfo as $company)
                <tr>
                    <td><input type="checkbox" value="{{ $company->company_id }}" name="selected[]"></td>
                    <td>{{ $company->company_name }}</td>
                    <td>{{ $company->company_abbreviation }}</td>
                    <td>{{ $company->company_phone }}</td>
                    <td>{{ $company->company_longitude }}</td>
                    <td>{{ $company->company_latitude }}</td>
                    <td>{{ date('Y-m-d H:i:s',$company->create_date) }}</td>
                    <td class="td-manage">
                        <a title="编辑" href="javascript:;" onclick="companyEdit({{ $company->company_id }})" class="ml-5" style="text-decoration:none">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="del({{ $company->company_id }})" style="text-decoration:none">
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
    layui.use(['element', 'layer', 'form'], function () {
        $ = layui.jquery;  // jquery
        lement = layui.element();  // 面包屑导航
        layer = layui.layer;  // 弹出层
        form = layui.form();  // 表单
    });

    function companyEdit (id) {
        window.location.href = "{{ url('Admin/Company/companyEditIndex') }}/id/" + id;
    }

    function batchDelete() {
        var checkbox = $('input[type=\'checkbox\']').is(':checked');

        if (false === checkbox) {
            layer.alert('请先选择要删除的数据');
        } else {
            layer.confirm('确认要删除吗？', function () {
                $('#company_form').submit();
            }, function () {
                layer.close();
            });
        }
    }

    function del(company_id){
        layer.confirm('确认要删除吗？', function () {
            // 确定
            window.location.href = '{{ url('Admin/Company/delete/id') }}/' + company_id;
        }, function () {
            // 取消
            layer.close();
        });
    }
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/Company/index') }}";

        var search_name = $('input[name=\'search_name\']').val();
        if (search_name) {
            url += '?search_name=' + encodeURIComponent(search_name);
        }

        var search_phone = $('input[name=\'search_phone\']').val();
        if (search_phone) {
            url += '?search_phone=' + encodeURIComponent(search_phone);
        }

        var search_abbreviation = $('input[name=\'search_abbreviation\']').val();
        if (search_abbreviation) {
            url += '?search_abbreviation=' + encodeURIComponent(search_abbreviation);
        }

        location = url;
    });
</script>
</body>
</html>