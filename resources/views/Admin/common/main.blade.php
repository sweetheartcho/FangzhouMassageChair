@include('Admin.common.meta')
<body>
<div class="layui-layout layui-layout-admin">
    @include('Admin.common.head')

    @include('Admin.common.leftmenu')

    <div class="layui-tab layui-tab-card site-demo-title x-main" lay-filter="x-tab" lay-allowclose="true">
        <div class="x-slide_left"></div>
        <ul class="layui-tab-title">
            <li class="layui-this">
                我的桌面
                <i class="layui-icon layui-unselect layui-tab-close">ဆ</i>
            </li>
        </ul>
        <div class="layui-tab-content site-demo site-demo-body">
            <div class="layui-tab-item layui-show" style="margin-top:50px; margin-left:10px;">
                @yield('content')
                <iframe frameborder="0" src="{{ url('Admin/Home/index') }}" name="abc" class="x-iframe"></iframe>

            </div>
        </div>
    </div>
    <div class="site-mobile-shade">
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('lib/bootstrap/js/bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/x-admin.js') }}"></script>
</body>
</html>