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
    <div class="layui-form x-center layui-form-pane search_form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" name="search_recommender_code"  placeholder="请输入推荐人" value="{{ $search_recommender_code }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_card_name"  placeholder="请输入令牌名称" value="{{ $search_card_name }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="search_company_name"  placeholder="请输入商家" value="{{ $search_company_name }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline" style="width:200px;">
                <select name="search_state">
                    <option value="*">请选择状态</option>
                    @foreach($states as $state)
                        @if(!empty($search['state'])&&$search['state']!='*'&&$search['state']==$state['value'])
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
    <span class="x-right" style="line-height:40px; width:150px;">共有数据：{{ $buyrecordNum }} 条</span>
    <table class="layui-table">
        <thead>
        <tr>
            @foreach($sort_route as $route)
                @if('nickname' == $sort)
                    <th><a href="{{ $route['nickname'] }}">购买人</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['nickname'] }}">购买人</a></th>
                @endif
                @if('user_telephone' == $sort)
                    <th><a href="{{ $route['user_telephone'] }}">联系方式</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['user_telephone'] }}">联系方式</a></th>
                @endif
                @if('recommender_code' == $sort)
                    <th><a href="{{ $route['recommender_code'] }}">推荐人</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['recommender_code'] }}">推荐人</a></th>
                @endif
                @if('card_name' == $sort)
                    <th><a href="{{ $route['card_name'] }}">令牌名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['card_name'] }}">令牌名称</a></th>
                @endif
                @if('mark1' == $sort)
                    <th><a href="{{ $route['mark1'] }}">价格（单位：元）</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['mark1'] }}">价格（单位：元）</a></th>
                @endif
                @if('company_name' == $sort)
                    <th><a href="{{ $route['company_name'] }}">商家</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['company_name'] }}">商家</a></th>
                @endif
                @if('operate_description' == $sort)
                    <th><a href="{{ $route['operate_description'] }}">描述</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['operate_description'] }}">描述</a></th>
                @endif
                @if('asset_state' == $sort)
                    <th><a href="{{ $route['asset_state'] }}">状态</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['asset_state'] }}">状态</a></th>
                @endif
                @if('create_date' == $sort)
                    <th><a href="{{ $route['create_date'] }}">购买时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['create_date'] }}">购买时间</a></th>
                @endif
                @if('asset_deadline' == $sort)
                    <th><a href="{{ $route['asset_deadline'] }}">到期时间</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                @else
                    <th><a href="{{ $route['asset_deadline'] }}">到期时间</a></th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(empty($buyrecord))
            <tr><td class="text-center" colspan="10">没有符合条件的结果！</td></tr>
        @else
            @foreach($buyrecord as $buy)
                <tr>
                    <td>{{ $buy->nickname }}</td>
                    <td>{{ $buy->user_telephone }}</td>
                    <td>{{ $buy->recommender_code }}</td>
                    <td>{{ $buy->card_name }}</td>
                    <td>{{ ($buy->mark1)*0.01 }}</td>
                    <td>{{ $buy->company_name }}</td>
                    <td>{{ $buy->operate_description }}</td>
                    <td>
                        @if(0 == $buy->asset_state)
                            正常
                        @elseif(1 == $buy->asset_state)
                            已过期
                        @elseif(2 == $buy->asset_state)
                            后台停用
                        @endif
                    </td>
                    <td>{{ $buy->create_date }}</td>
                    <td>{{ $buy->asset_deadline }}</td>
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
        var url = "{{ url('Admin/Record/buyIndex') }}";

        var search_recommender_code = $('input[name=\'search_recommender_code\']').val();
        if (search_recommender_code) {
            url += '?search_recommender_code=' + encodeURIComponent(search_recommender_code);
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
        if (search_state != '*') {
            url += '?search_state=' + search_state;
        }

        location = url;
    });
</script>
</body>
</html>