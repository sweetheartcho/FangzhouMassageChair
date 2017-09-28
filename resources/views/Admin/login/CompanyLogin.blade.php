<!DOCTYPE HTML>
<html>
<head>
    <title>方舟按摩椅管理后台登录页面</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="方舟，按摩椅" />
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" media="all"/>
</head>
<body>
<div class="login">
    <div class="login-top">
        <h1>方舟后台</h1>
        <form id="login_form" method="post" action="">
            {{ csrf_field() }}
            <input name="Company[account]" type="text" placeholder="用户帐号" id="input-name">
            <input name="Company[password]" type="password" placeholder="密码" id="input-pwd">
            <div class="forgot">
                <input type="button" value="登录" id="submit-button">
            </div>
        </form>
    </div>
</div>
<div class="copyright">
    <p>Copyright &copy; 2017.Company name All rights reserved.</p>
</div>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    layui.use(['laydate','element','laypage','layer'], function(){
        $ = layui.jquery; //jquery
        layer = layui.layer; //弹出层
    });

    $(function () {
        $(document).keydown(function (event) {
            if (event.keyCode == 13) {
                $("#submit-button").click();
            }
        });

        $("#submit-button").click(function(){
            var name = $('#input-name').val();
            var pwd = $('#input-pwd').val();

            if ('' == name) {
                layer.msg('用户名不能为空', {icon: 2});
                return false;
            }else if ('' == pwd) {
                layer.msg('密码不能为空', {icon: 2});
                return false;
            }

            $.ajax({
                type: 'post',
                url: "{{ url('Admin/Login/CompanyLogin') }}",
                data: {Company: {account: name, password: pwd}},
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                success: function (msg) {
                    console.log(msg);

                    if ('2' == msg) {
                        window.location.href = "{{ url('Admin/Home/index') }}";
                    } else if ('1' == msg) {
                        layer.msg('用户名或密码错误', {icon: 2, time: 2000});
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    })

</script>
</body>
</html>