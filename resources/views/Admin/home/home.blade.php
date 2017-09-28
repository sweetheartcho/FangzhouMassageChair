@include('Admin.common.meta')
<body>
@if(Session::has('authority_id'))
    @if('1' == Session::get('authority_id'))
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="tile">
                <div class="tile-heading">最近购买令牌</div>
                <div class="tile-body">
                    <i class="icon iconfont icon-dunpai"></i>
                    <h2 class="pull-right">{{ $latestCard }}</h2>
                </div>
                <div class="tile-footer"><a href="{{ url('Admin/Record/buyIndex') }}" target="main">查看更多...</a></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="tile">
                <div class="tile-heading">即将过期令牌</div>
                <div class="tile-body">
                    <i class="icon iconfont icon-dunpai"></i>
                    <h2 class="pull-right">{{ $outDateCard }}</h2>
                </div>
                <div class="tile-footer"><a href="{{ url('Admin/Record/buyIndex') }}" target="main">查看更多...</a></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="tile">
                <div class="tile-heading">异常订单</div>
                <div class="tile-body">
                    <i class="icon iconfont icon-yichang"></i>
                    <h2 class="pull-right">{{ $unusualOrder }}</h2>
                </div>
                <div class="tile-footer"><a href="{{ url('Admin/Record/buyIndex') }}" target="main">查看更多...</a></div>
            </div>
        </div>
    @elseif('2' == Session::get('authority_id') || '3' == Session::get('authority_id'))
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 style="font-size: 50px; text-align: center; padding-top: 300px;">欢迎登录后台管理系统</h1>
        </div>
    @endif
@endif
</body>
</html>