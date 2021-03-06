@extends('daili.layouts.main')
@section('content')

    <section class="content">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">代理报表</h3>
            </div>
            <div class="panel-body">
                @include('daili.member_daili.filter')

                <table class="table table-bordered table-hover text-center">
                    <tr>
                        <th style="width: 50%">存款总额</th>
                        <th style="width: 50%">存款笔数</th>
                    </tr>
                    <tr>
                        <td>{{ $total_recharge }}</td>
                        <td>{{ $recharge_count }}</td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover text-center">
                    <tr>
                        <th style="width: 50%">提款总额</th>
                        <th style="width: 50%">提款笔数</th>
                    </tr>
                    <tr>
                        <td>{{ $total_drawing }}</td>
                        <td>{{ $drawing_count }}</td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover text-center">
                    <tr>
                        <th style="width: 50%">红利总额</th>
                        <th style="width: 50%">红利笔数</th>
                    </tr>
                    <tr>
                        <td>{{ $total_dividend }}</td>
                        <td>{{ $dividend_count }}</td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover text-center">
                    <tr>
                        <th style="width: 50%">盈利</th>
                        <th style="width: 50%">会员输赢</th>
                    </tr>
                    <tr>
                        <td>{{ $total_recharge - $total_drawing - $total_dividend }}</td>
                        <td>{{ $total_drawing -  $total_recharge }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </section><!-- /.content -->
@endsection
@section("after.js")
    <script>
        var start = {
            elem: '#start_at',
            format: 'YYYY-MM-DD hh:mm:ss',
            //min: laydate.now(), //设定最小日期为当前日期
            max: '2099-06-16 23:59:59', //最大日期
            istime: true,
            istoday: false,
            choose: function(datas){
                end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };
        var end = {
            elem: '#end_at',
            format: 'YYYY-MM-DD 23:59:59',
            //min: laydate.now(),
            max: '2099-06-16 23:59:59',
            istime: true,
            istoday: true,
            choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        laydate(start);
        laydate(end);
    </script>
@endsection