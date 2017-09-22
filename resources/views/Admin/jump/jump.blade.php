<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>跳转提示</title>
    <link type="text/css" rel="stylesheet" href="{{ asset('css/404.css') }}"/>
</head>
<body>
<div class="mianBox">
    <img src="{{ asset('images/yun0.png') }}" alt="" class="yun yun0"/>
    <img src="{{ asset('images/yun1.png') }}" alt="" class="yun yun1"/>
    <img src="{{ asset('images/yun2.png') }}" alt="" class="yun yun2"/>
    <img src="{{ asset('images/bird.png') }}" alt="" class="bird"/>
    <img src="{{ asset('images/san.png') }}" alt="" class="san"/>
    <div class="tipInfo">
        <div class="in">
            <div class="textThis">
                <h2>{{ $data['message'] }}</h2>
                <p><span>等待<b id="wait">{{ $data['jumpTime'] }}</b>秒</span><span>页面自动<a id="href" href="{{ asset($data['url']) }}">跳转</a></span></p>
                <script type="text/javascript">
                    (function () {
                        var wait = document.getElementById('wait'), href = document.getElementById('href').href;
                        var interval = setInterval(function () {
                            var time = --wait.innerHTML;
                            if (time <= 0) {
                                location.href = href;
                                clearInterval(interval);
                            };
                        }, 1000);
                    })();
                </script>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        var h = $(window).height();
        $('body').height(h);
        $('.mianBox').height(h);
        centerWindow(".tipInfo");
    });

    //2.将盒子方法放入这个方，方便法统一调用
    function centerWindow(a) {
        center(a);
        //自适应窗口
        $(window).bind('scroll resize',
            function () {
                center(a);
            });
    }

    //1.居中方法，传入需要剧中的标签
    function center(a) {
        var wWidth = $(window).width();
        var wHeight = $(window).height();
        var boxWidth = $(a).width();
        var boxHeight = $(a).height();
        var scrollTop = $(window).scrollTop();
        var scrollLeft = $(window).scrollLeft();
        var top = scrollTop + (wHeight - boxHeight) / 2;
        var left = scrollLeft + (wWidth - boxWidth) / 2;
        $(a).css({
            "top": top,
            "left": left
        });
    }
</script>
</body>
</html>