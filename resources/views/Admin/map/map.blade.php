<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <style type="text/css">
        body, html, #allmap {
            width: 100%;
            height: 100%;
            overflow: hidden;
            margin: 0;
            font-family: "微软雅黑";
        }
    </style>
    <title>异步加载地图</title>
</head>
<body>
<div id="allmap"></div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
    //百度地图API功能
    function loadJScript() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://api.map.baidu.com/api?v=2.0&ak=HIpRYQ5LvHxOTfNjydsSsdRWFTTUz9o4&callback=init";
        document.body.appendChild(script);
    }

    function init() {
        // 百度地图API功能
        var map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);
        map.enableScrollWheelZoom();    //启用滚轮放大缩小

        var local = new BMap.LocalSearch(map, {
            renderOptions: {map: map}
        });
        local.search("飞机场");

        //单击获取点击的经纬度
        map.addEventListener("click", function (e) {
            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

            parent.$("input[name='Company[company_longitude]']").val(e.point.lng);
            parent.$("input[name='Company[company_latitude]']").val(e.point.lat);
            parent.layer.close(index);

        });
    }

    window.onload = loadJScript;  //异步加载地图
</script>
</body>
</html>