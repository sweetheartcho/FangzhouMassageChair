@include('Admin.common.meta')
<body >
<div class="x-nav">
    <a class="layui-btn layui-btn-small"
       style="line-height:1.6em;margin-top:3px;float:left;padding-top:5px; margin-right:10px;"
       href="{{ url('Admin/Card/index') }}" title="返回上一页">
        <i class="layui-icon">&lt;</i>
    </a>
    <span class="layui-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <a><cite>{{ $breadcrumb['text'] }}</cite></a>
        @endforeach
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i>
    </a>
</div>
<div class="x-body">
    <form class="layui-form" method="post" action="{{ url('Admin/Card/CardEdit') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="card_id" value="{{ $card_id }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label">产品编号</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Card[card_name]" value="{{ $card_id}}" disabled="disabled" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">产品名称<span class="x-red">*</span></label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Card[card_name]" autocomplete="off"
                       value="{{ Session::has('card_name')?Session::pull('card_name'):$cardinfo->card_name }}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">产品价格</label>
            <div class="layui-input-inline-modify">
                <input type="text" name="Card[card_price]" autocomplete="off"
                       value="{{ Session::has('card_price')?Session::pull('card_price'):$cardinfo->card_price }}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">产品描述</label>
            <div class="layui-input-inline-modify">
                <textarea name="Card[card_description]" autocomplete="off"
                          class="layui-textarea">{{ Session::has('card_description')?Session::pull('card_description'):$cardinfo->card_description }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">产品logo展示</label>
            <div class="layui-input-inline" style="width: 500px;">
                <div class="col-sm-12">
                    <div class="thumbnail img-thumbnail-size img-list">
                        <img src="{{ asset($cardinfo->card_logo) }}" alt=""/>
                    </div>
                </div>
                <input type="hidden" name="Card[card_logo]" value=""/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">修改产品logo</label>
            <div class="layui-input-inline" style="width: 500px;">
                <div class="col-sm-12" id="image-show-area">
                    <span class="glyphicon glyphicon-plus-sign" id="addImage"></span>
                </div>
                <input type="hidden" name="Card[card_logo]" value=""/>
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
    layui.use(['element', 'form', 'layer'], function () {
        $ = layui.jquery;//jquery
        lement = layui.element();//面包导航
        layer = layui.layer;//弹出层
        form = layui.form(); //表单
    });

    $('#modify-button').click(function () {
        var card_name = $("input[name='Card[card_name]']").val();
        var card_price = $("input[name='Card[card_price]']").val();
        var card_logo = $('.img-body img').attr('src');

        $("input[name='Card[card_logo]']").val(card_logo);

        if ('' == card_name) {
            layer.alert('产品名称不能为空');
            return false;
        }

        if ('' == card_price) {
            layer.alert('产品价格不能为空');
            return false;
        }
    });
</script>
<script>

    window.onbeforeunload=function(){
        alert(1);
        alert($('#image-show-area').hasClass('img-body'));
        if ($('#image-show-area').hasClass('img-body')) {
            $('#addImage').hide();
        } else {
            $('#addImage').show();
        }
    };

    $('#addImage').click(function () {
        layer.open({
            type: 2,
            title: '上传图片',
            area: ['1100px', '750px'],
            shadeClose: true,
            content: "{{ url('Admin/Img/index') }}"
        });
    });
</script>
</body>
</html>