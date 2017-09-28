@include('Admin.common.meta')
<body>
<div class="col-sm-12 col-md-12 col-lg-12 img-menu">
    <a class="layui-btn" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:38px">ဂ</i></a>
    <form method="post" action="{{ url('Admin/Img/upload') }}" id="uploadimg" enctype="multipart/form-data">
        {{ csrf_field() }}
        <a class="file" href="javascript:;"  title="上传文件" style="margin-bottom:-15px;">上传<input type="file" name="uploadimg[]" id="upload-file-button"></a>
    </form>
    <button class="layui-btn layui-btn-danger" id="delete-button" title="删除">删除</button>
</div>
<div class="col-sm-12 col-md-12 col-lg-12">
    <form id="deleteform" method="post" action="{{ url('Admin/Img/delete') }}">
        {{ csrf_field() }}
            @foreach($images as $img)
                <div class="col-sm-3 col-md-3 col-lg-3 img-list">
                    <div class="thumbnail img-thumbnail-size select-img">
                        <img src="{{ asset($img['filepath']) }}" alt="{{ $img['filename'] }}"/>
                    </div>
                    <label class="text-center">
                        <input type="checkbox" name="imgpath[]" value="{{ $img['filepath'] }}"/>{{ $img['filename'] }}
                    </label>
                </div>
            @endforeach
    </form>
    <div class="pull-right">{!! $paginator->render() !!}</div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/x-layui.js') }}" charset="utf-8"></script>
<script>
    layui.use(['layer', 'form'], function () {
        $ = layui.jquery;  // jquery
        layer = layui.layer;  // 弹出层
        form = layui.form();  // 表单
    });

    // 选中图片
    $('.select-img img').one('click',function () {
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

        var html = "<div class='col-sm-3 img-thumbnail img-body'>" +
                   "<img src='" + $(this).attr('src') + "'/>" +
                   "<a onclick=\"$(this).parent().remove();\" class='glyphicon glyphicon-remove' role='menuitem' tabindex='-1'>删除</a>" +
                   "</div>";

        parent.$('#addImage').before(html);

        parent.layer.close(index);

    });
</script>
<script>
    $('#upload-file-button').change(function () {
        var file = $("input[type='file']").val().toLowerCase();

        if (file.indexOf('png') != -1 || file.indexOf('gif') != -1 || file.indexOf('jpg') != -1 || file.indexOf('jpeg') != -1) {
            if (($("input[type='file']")[0].files[0].size).toFixed(1) >= (1024 * 1024)) {
                layer.alert('请上传小于1M的图片');
                return false;
            } else {
                $.ajax({
                    url: "{{ url('Admin/Img/upload') }}",
                    type: 'POST',
                    data: new FormData($('#uploadimg')[0]),
                    dataType: 'JSON',
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        parent.layer.alert(msg, function () {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        layer.alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        } else {
            layer.alert('请选择正确的图片格式');
            return false;
        }
    });
</script>
<script>
    $('#delete-button').click(function () {
        var checkbox = $("input[type='checkbox']").is(':checked');

        if (false === checkbox) {
            layer.alert('请选择要删除的图片');
            return false;
        } else {
            $.ajax({
                url: "{{ url('Admin/Img/delete') }}",
                type: 'POST',
                data: new FormData($('#deleteform')[0]),
                dataType: 'JSON',
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                cache: false,
                processData: false,
                contentType: false,
                success: function (msg) {
                    parent.layer.alert(msg, function () {
                        window.location.reload();
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    layer.alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
</script>
</body>
</html>