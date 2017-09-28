<div class="layui-header header header-demo">
    <div class="layui-main">
        <span class="logo">方舟按摩椅后台管理</span>
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item"><img src="{{ asset('images/logo.png') }}" class="layui-circle" style="border: 2px solid #A9B7B7;" width="35px" alt=""></li>
            <li class="layui-nav-item">
                <a href="javascript:;">{{ Session::get('account') }}</a>
                <dl class="layui-nav-child"> <!-- 二级菜单 -->
                    @if(Session::has('authority_id'))
                        @if('1' == Session::get('authority_id'))
                            <dd><a href="{{ asset('Admin/Login/AdminLogout') }}">退出</a></dd>
                        @elseif('2' == Session::get('authority_id'))
                            <dd><a href="{{ asset('Admin/Login/CompanyLogout') }}">退出</a></dd>
                        @elseif('3' == Session::get('authority_id'))
                            <dd><a href="{{ asset('Admin/Login/MerchantLogout') }}">退出</a></dd>
                        @endif
                    @endif
                </dl>
            </li>
        </ul>
    </div>
</div>