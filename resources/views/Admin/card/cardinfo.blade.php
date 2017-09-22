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
                <input type="text" name="search_name" placeholder="请输入令牌名称" value="{{ $search_name }}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline" style="width:80px">
                <button class="layui-btn" id="search-button" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="batchDelete()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        <span>（红色代表商品已下架）</span>
        <span class="x-right" style="line-height:40px">共有数据：{{ $cardnum }} 条</span>
    </xblock>
    <form method="post" action="{{ url('Admin/Card/batchDelete') }}" id="product_form">
        {{ csrf_field() }}
        <table class="layui-table">
            <thead>
            <tr>
                <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked',this.checked);"></th>
                <th>产品编号</th>
                @foreach($sort_route as $route)
                    @if('card_name'==$sort)
                        <th><a href="{{ $route['card_name'] }}">令牌名称</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                    @else
                        <th><a href="{{ $route['card_name'] }}">令牌名称</a></th>
                    @endif
                        @if('card_price'==$sort)
                            <th><a href="{{ $route['card_price'] }}">价格</a><span class="glyphicon glyphicon-chevron-down pull-right"></span></th>
                        @else
                            <th><a href="{{ $route['card_price'] }}">价格</a></th>
                        @endif
                @endforeach
                <th>描述</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($cardinfo))
                <tr><td class="text-center" colspan="6">没有符合条件的结果！</td></tr>
            @else
                @foreach($cardinfo as $card)
                    @if(1 == $card->card_state)
                        <tr style="color:#FF0000;">
                            <td><input type="checkbox" value="{{ $card->card_id }}" name="selected[]"></td>
                            <td>{{ $card->card_id }}</td>
                            <td>{{ $card->card_name }}</td>
                            <td>{{ $card->card_price }}</td>
                            <td>{{ $card->card_description }}</td>
                            <td class="td-manage">
                                <a title="@if('0'== $card->card_state) 下架 @else 上架 @endif" href="{{ url('Admin/Card/stopOrStart/card_id',['id'=>$card->card_id]) }}" style="text-decoration:none">
                                    <i class="layui-icon" style="color:#FF0000;">&#xe601;</i>
                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td><input type="checkbox" value="{{ $card->card_id }}" name="selected[]"></td>
                            <td>{{ $card->card_id }}</td>
                            <td>{{ $card->card_name }}</td>
                            <td>{{ $card->card_price }}</td>
                            <td>{{ $card->card_description }}</td>
                            <td class="td-manage">
                                <a title="@if('0'== $card->card_state) 下架 @else 上架 @endif" href="{{ url('Admin/Card/stopOrStart/card_id',['id'=>$card->card_id]) }}" style="text-decoration:none">
                                    <i class="layui-icon">&#xe601;</i>
                                </a>
                                <a title="编辑" href="javascript:;" onclick="cardEdit('{{ $card->card_id }}')" class="ml-5" style="text-decoration:none">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" href="javascript:;" onclick="del('{{ $card->card_id }}')" style="text-decoration:none">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                        </tr>
                    @endif
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


    function cardEdit(card_id) {
        window.location.href = "{{ url('Admin/Card/cardEditIndex/card_id') }}/" + card_id;
    }

    function del(card_id) {
        layer.confirm('确定要删除吗？', function () {
            window.location.href = "{{ url('Admin/Card/delete/card_id') }}/" + card_id;
        }, function () {
            layer.close();
        });
    }

    function batchDelete() {
        var checkbox = $('input[type=\'checkbox\']').is(':checked');

        if (false === checkbox) {
            layer.alert('请先选择要删除的数据');
        } else {
            layer.confirm('确认要删除吗？', function () {
                $('#product_form').submit();
            }, function () {
                layer.close();
            });
        }
    }
</script>
<script>
    $('#search-button').click(function () {
        var url = "{{ url('Admin/Card/index') }}";

        var search_name = $('input[name=\'search_name\']').val();
        if (search_name) {
            url += '?search_name=' + encodeURIComponent(search_name);
        }

        location = url;
    });
</script>
</body>
</html>