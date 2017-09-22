<!-- 成功提示框 -->
@if (session()->has('success'))
    <div class="alert alert-info"><strong>成功!</strong> {{ session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<!-- 失败提示框 -->
@if (session()->has('errer'))
    <div class="alert alert-danger"><strong>失败!</strong> {{ session()->get('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif