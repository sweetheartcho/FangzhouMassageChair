@include('Admin.common.meta')
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;"  href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-form x-center layui-form-pane search-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="search_mark1" placeholder="请输入推荐人" value="{{ $search_mark1 }}" autocomplete="off" class="layui-input" id="search-nickname">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_card_name" placeholder="请输入令牌名称" value="{{ $search_card_name }}" autocomplete="off" class="layui-input" id="search-phone">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_company_name" placeholder="请输入机场" value="{{ $search_company_name }}" autocomplete="off" class="layui-input" id="search-token">
            </div>
            <div class="layui-input-inline" style="width:200px;">
                <select name="search_state">
                    @foreach($states as $state)
                        @if($state['value'] == $search_state)
                            <option value="{{ $state['value'] }}" selected>{{ $state['title'] }}</option>
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
    <span class="x-right" style="line-height:40px; width:150px;">共有数据：{{ $employrecordNum }} 条</span>
    <table class="layui-table">
        <thead>
        <tr>
            @foreach($sort_route as $route)
                @if('nickname' == $sort)
                    <th><a href="{{ $route['nickname'] }}">使用人</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['nickname'] }}">使用人</a></th>
                @endif
                @if('user_telephone' == $sort)
                    <th><a href="{{ $route['user_telephone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['user_telephone'] }}">联系方式</a></th>
                @endif
                @if('mark1' == $sort)
                    <th><a href="{{ $route['mark1'] }}">推荐人</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['mark1'] }}">推荐人</a></th>
                @endif
                @if('card_name' == $sort)
                    <th><a href="{{ $route['card_name'] }}">令牌名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['card_name'] }}">令牌名称</a></th>
                @endif
                @if('company_name' == $sort)
                    <th><a href="{{ $route['company_name'] }}">机场</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_name'] }}">机场</a></th>
                @endif
                @if('use_time' == $sort)
                    <th><a href="{{ $route['use_time'] }}">体验时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['use_time'] }}">体验时间</a></th>
                @endif
                @if('mark2' == $sort)
                    <th><a href="{{ $route['mark2'] }}">座椅类型</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['mark2'] }}">座椅类型</a></th>
                @endif
                @if('operate_description' == $sort)
                    <th><a href="{{ $route['operate_description'] }}">描述</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['operate_description'] }}">描述</a></th>
                @endif
                @if('operate_date' == $sort)
                    <th><a href="{{ $route['operate_date'] }}">使用时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['operate_date'] }}">使用时间</a></th>
                @endif
                @if('is_state' == $sort)
                    <th><a href="{{ $route['is_state'] }}">状态</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['is_state'] }}">状态</a></th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(empty($employrecord))
            <tr><td class="text-center" colspan="10">没有符合条件的结果！</td></tr>
        @else
            @foreach($employrecord as $employ)
                <tr>
                    <td>{{ $employ->nickname }}</td>
                    <td>{{ $employ->user_telephone }}</td>
                    <td>{{ $employ->mark1 }}</td>
                    <td>{{ $employ->card_name }}</td>
                    <td>{{ $employ->company_name }}</td>
                    <td>{{ $employ->use_time }}分钟</td>
                    <td>
                        @if(1 == $employ->mark2)
                            普通型
                        @elseif(2 == $employ->mark2)
                            豪华型
                        @endif
                    </td>
                    <td>{{ $employ->operate_description }}</td>
                    <td>{{ $employ->operate_date }}</td>
                    <td>
                        @if(0 == $employ->is_state)
                            正常
                        @elseif(1 == $employ->is_state)
                            超限
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="pull-right">{!! $paginator->render() !!}</div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['element','layer','form'], function(){
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        layer = layui.layer;//弹出层
        form = layui.form();
    });
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/Record/employIndex') }}";

        var search_mark1 = $('input[name=\'search_mark1\']').val();
        if (search_mark1) {
            url += '?search_mark1=' + encodeURIComponent(search_mark1);
        }

        var search_card_name = $('input[name=\'search_card_name\']').val();
        if (search_card_name) {
            url += '?search_card_name=' + encodeURIComponent(search_card_name);
        }

        var search_company_name = $('input[name=\'search_company_name\']').val();
        if (search_company_name) {
            url += '?search_company_name=' + encodeURIComponent(search_company_name);
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
