@include('Admin.common.meta')
<body>
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
</body>
</html>