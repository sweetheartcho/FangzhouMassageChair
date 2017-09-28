@include('Admin.common.meta')
<body>
<div class="x-nav">
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
    <form class="layui-form" method="post" action="{{ url('Admin/Config/editimg') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">商店顶部图片</label>
            <div class="layui-input-inline" style="width:500px;">
                <div class="col-sm-12">
                    @foreach($configimg as $img)
                        <div class="col-sm-3 img-thumbnail image-show-area">
                            <img src="{{ asset($img->card_photo) }}" alt="{{ $img->card_photo }}"/>
                            <a class="glyphicon glyphicon-remove" onclick="deleteImg('{{ $img->config_Id }}');">删除</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">修改图片</label>
            <div class="layui-input-inline" style="width:500px;">
                <div class="col-sm-12">
                    <span class="glyphicon glyphicon-plus-sign" id="addImage"></span>
                </div>
                <input type="hidden" name="card_photo" value=""/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <button class="layui-btn" id="save-button">保存</button>
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

    $('#addImage').click(function () {
        layer.open({
            type: 2,
            title: '上传图片',
            area: ['1100px', '750px'],
            shadeClose: true,
            content: "{{ url('Admin/Img/index') }}"
        });
    });

    $('#save-button').click(function () {
        var image_src = [];
        $('.img-body img').each(function () {
            image_src.push($(this).attr('src'));
        });

        $("input[name='card_photo']").val(image_src);
    });
</script>
<script>
    function deleteImg(configId) {
        $.ajax({
            type: 'POST',
            url: "{{ url('Admin/Config/deleteImg') }}",
            data: {id: configId},
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function (msg) {
                layer.alert(msg, function () {
                    window.location.reload();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
</script>
</body>
</html>