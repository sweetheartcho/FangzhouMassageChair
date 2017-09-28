@include('Admin.common.meta')
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-form x-center layui-form-pane search-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="search_nickname" placeholder="请输入姓名" value="{{ $search_nickname }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_phone" placeholder="请输入联系方式" value="{{ $search_phone }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_token" placeholder="请输入令牌名称" value="{{ $search_token }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline" style="width:200px;">
                <select name="search_state" id="search_state">
                    @foreach($states as $state)
                        @if($state['value']==$search_state)
                            <option value="{{ $state['value'] }}" selected="selected">{{ $state['title'] }}</option>
                        @else
                            <option value="{{ $state['value'] }}">{{ $state['title'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="layui-input-inline" style="width:80px">
                <button class="layui-btn" id="search-button" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" id="batch-delete" onclick="batchDelete()">
            <i class="layui-icon">&#xe640;</i>批量删除
        </button>
        <span class="x-right" style="line-height:40px">共有数据：{{ $usernum }} 条</span>
    </xblock>
    <form method="post" action="{{ url('Admin/User/batchDelete') }}" id="user_form">
        {{ csrf_field() }}
        <table class="layui-table">
            <thead>
            <tr>
                <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);"></th>
                @foreach($sort_route as $route)
                    @if('nickname' == $sort)
                        <th><a href="{{ $route['nickname'] }}">姓名</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['nickname'] }}">姓名</a></th>
                    @endif
                    @if('sex' == $sort)
                        <th><a href="{{ $route['sex'] }}">性别</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['sex'] }}">性别</a></th>
                    @endif
                    @if('phone' == $sort)
                        <th><a href="{{ $route['phone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['phone'] }}">联系方式</a></th>
                    @endif
                    @if('token' == $sort)
                        <th><a href="{{ $route['token'] }}">令牌名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['token'] }}">令牌名称</a></th>
                    @endif
                    @if('create_date' == $sort)
                        <th><a href="{{ $route['create_date'] }}">购买时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['create_date'] }}">购买时间</a></th>
                    @endif
                    @if('state' == $sort)
                        <th><a href="{{ $route['state'] }}">状态</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['state'] }}">状态</a></th>
                    @endif
                @endforeach
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($userinfo))
                <tr><td class="text-center" colspan="8">没有符合条件的结果!</td></tr>
            @else
                @foreach($userinfo as $user)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $user->id }}" name="selected[]">
                        </td>
                        <td>{{ $user->nickname }}</td>
                        <td>
                            @if('1' == $user->sex)
                                男
                            @elseif('0' === $user->sex)
                                女
                            @endif
                        </td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->token }}</td>
                        <td>{{ $user->create_date }}</td>
                        <td class="td-status">
                            <span class="layui-btn layui-btn-normal layui-btn-mini">
                                @if('1' == $user->state)
                                    正常
                                @elseif('0' === $user->state)
                                    禁用
                                @endif
                            </span>
                        </td>
                        <td class="td-manage">
                            <a title="@if('1'== $user->state) 禁用 @else 启用 @endif" href="{{ url('Admin/User/stopOrStart/id',['id'=>$user->id]) }}" style="text-decoration:none">
                                <i class="layui-icon">&#xe601;</i>
                            </a>
                            <a title="删除" href="javascript:void(0)" style="text-decoration:none"onclick="del({{ $user->id }}); "><i class="layui-icon">&#xe640;</i></a>
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
    layui.use(['element', 'layer', 'form'], function () {
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        layer = layui.layer;//弹出层
        form = layui.form();
    });

    function batchDelete() {
        var checkbox = $("input[type='checkbox']").is(':checked');

        if (false === checkbox) {
            layer.alert('请先选择要删除的数据');
        } else {
            layer.confirm('确定要删除吗？', function () {
                $('#user_form').submit();
            }, function () {
                layer.close();
            });
        }
    }

    function del(userId) {
        layer.confirm('确定要删除吗？', function () {
            window.location.href = "{{ url('Admin/User/delete/id') }}/" + userId;
        }, function () {
            layer.close();
        })
    }
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/User/index') }}";

        var search_nickname = $('input[name=\'search_nickname\']').val();
        if (search_nickname) {
            url += '?search_nickname=' + encodeURIComponent(search_nickname);
        }

        var search_phone = $('input[name=\'search_phone\']').val();
        if (search_phone) {
            url += '?search_phone=' + encodeURIComponent(search_phone);
        }

        var search_token = $('input[name=\'search_token\']').val();
        if (search_token) {
            url += '?search_token=' + encodeURIComponent(search_token);
        }

        var search_state = $('select[name=\'search_state\']').val();
        if (search_state != '-1') {
            url += '?search_state=' + search_state;
        }

        location = url;
    });
</script>
</body>
</html>
