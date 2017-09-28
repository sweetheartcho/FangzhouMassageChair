<div class="layui-side layui-bg-black x-side">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree site-demo-nav" lay-filter="side">
            @if(Session::has('authority_id'))
                @if('1' == Session::get('authority_id'))
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe612;</i><cite>信息管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/User/index') }}">
                                    <cite>用户管理</cite>
                                </a>
                            </dd>
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Company/index') }}">
                                    <cite>商户管理</cite>
                                </a>
                            </dd>
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Company/addCompanyIndex') }}">
                                    <cite>添加商户</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe630;</i><cite>贵宾厅管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Merchant/index') }}">
                                    <cite>贵宾厅列表</cite>
                                </a>
                            </dd>
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Merchant/addMerchantIndex') }}">
                                    <cite>添加贵宾厅</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe62d;</i><cite>商品管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Card/index') }}">
                                    <cite>商品管理</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe642;</i><cite>操作记录</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Record/buyIndex') }}">
                                    <cite>购买记录</cite>
                                </a>
                            </dd>
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Record/employIndex') }}">
                                    <cite>使用记录</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe614;</i><cite>系统设置</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Config/index') }}">
                                    <cite>基本设定</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                @elseif('2' == Session::get('authority_id'))
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe612;</i><cite>信息管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Company/index') }}">
                                    <cite>商户管理</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                @elseif('3' == Session::get('authority_id'))
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe612;</i><cite>信息管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Company/index') }}">
                                    <cite>商户管理</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a class="javascript:;" href="javascript:;">
                            <i class="layui-icon" style="top: 3px;">&#xe630;</i><cite>贵宾厅管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="">
                                <a href="javascript:;" _href="{{ url('Admin/Merchant/index') }}">
                                    <cite>贵宾厅列表</cite>
                                </a>
                            </dd>
                        </dl>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>