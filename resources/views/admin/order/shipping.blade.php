<div class="panel panel-default">
    <div class="panel-heading">发货清单</div>
    <div class="panel-body">
        <form action="/admin/api/shipping" method="post">
            <div class="form-group">
                <div class="form-inline">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" >
                    <input type="hidden" value="{{$id}}" name="id">
                    <button type="submit" class="btn btn-primary">确定发货</button>
{{--                    <button type="button" class="btn btn-info" onclick="print_view()">预览清单</button>
                    <button type="button" class="btn btn-info" onclick="print()">打印清单</button>--}}
                </div>
            </div>
            <div class="form-group" id="list">
                <table style="font-size: 14px; text-align: center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="10">拣货清单</td>
                    </tr>
                    <tr>
                        <td colspan="5">单号：{{$orderid}}</td>
                        <td colspan="5">发货人：{{$username}}</td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 200px">名称</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 100px">产品货号</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 50px">数量</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 80px">批号</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 80px">灭菌批号</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 80px">生产日期</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 80px">失效日期</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 80px">装箱规格</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 50px">原产地</td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;width: 100px">备注/说明</td>
                    </tr>
                    @foreach($products as $key => $product)
                        <tr>
                            <td>{{$product['medicinal']}}</td>
                            <td>{{$product['medicinalnum']}}</td>
                            <td>{{$product['num']}}</td>
                            <td>{{$product['batchnumber']}}</td>
                            <td>{{$product['novirus']}}</td>
                            <td>{{$product['makedate']}}</td>
                            <td>{{$product['invalidate']}}</td>
                            <td>{{$product['boxformat']}}</td>
                            <td>{{$product['originmake']}}</td>
                            <td>{{$product['tips']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<script language="javascript" src="/js/LodopFuncs.js"></script>
<script>
    function print_view(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('list').innerHTML);
        LODOP.PREVIEW();
    }
    function print(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('list').innerHTML);
        LODOP.PRINT();
    }
</script>