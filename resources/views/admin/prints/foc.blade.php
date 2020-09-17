<script language="javascript" src="/js/LodopFuncs.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">{{$title}}</div>
    <div class="panel-body" id="table" >
        <div class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2">销售订单/FOC 申请表</label>
                <div class="col-sm-8" id="foc">
                    <table style="font-size: 14px; width: 500px" cellpadding="0" cellspacing="0">
                        <tr><td colspan="7" align="center" style="font-size: 16px; font-weight: bold">销售订单/FOC 申请表</td></tr>
                        <tr><td colspan="4">{{$focname[0]}}</td><td colspan="3">{{$focname[1]}}</td></tr>
                        <tr><td colspan="7">{{$focname[2]}}</td></tr>
                        <tr>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][0]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][1]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][2]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][3]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][4]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][5]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000">{{$salefoc['listtitle'][6]}}</td>
                        </tr>
                        @foreach($lists as $key=>$list)
                            <tr>
                                <td align="center" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$list['medicinalnum']}}</td>
                                <td align="center" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$list['medicinal']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$list['unit']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000">{{$list['num']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000">{{$list['price']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$list['prices']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000">{{$list['tips']}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtotal'][0]}}：{{$total}}元</td>
                            <td style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000"></td>
                        </tr>
                        <tr><td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000; height:10px;" colspan="7"></td></tr>
                        <tr>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000;">{{$salefoc['foctitle'][0]}}</td>
                            <td colspan="4" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$salefoc['foctitle'][1]}}</td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000;">{{$salefoc['foctitle'][2]}}</td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000">{{$salefoc['foctitle'][3]}}</td>
                        </tr>
                        @foreach($lists as $key=>$list)
                            <tr>
                                <td style="border-top: 1px solid #000; border-left:1px solid #000; height: 35px;"><input type="text" class="form-control" ></td>
                                <td colspan="4" style="border-top: 1px solid #000; border-left:1px solid #000;height: 35px;"><input type="text" class="form-control" ></td>
                                <td style="border-top: 1px solid #000; border-left:1px solid #000;height: 35px;"><input type="text" class="form-control" ></td>
                                <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000;height: 35px;"><input type="text" class="form-control" ></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" style="border-top: 1px solid #000;border-left:1px solid #000; border-bottom: 1px solid #000;height: 35px;"><input type="text" class="form-control" placeholder="{{$salefoc['foctotal'][0]}}"> </td>
                            <td style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000;border-bottom: 1px solid #000"></td>
                        </tr>
                        <tr>
                            <td colspan="7" style="height: 100px">客户盖章：</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <label class="control-label col-sm-2"></label>
        <div class="form-group">
            <div class="button-group col-sm offset-2">
                <button type="button" class="btn btn-success btnsure">确定</button>
                <button type="button" class="btn btn-info" onclick="print_view();">预览</button>
                <button type="button" class="btn btn-info" onclick="print();">打印</button>
            </div>
        </div>
    </div>
</div>

<script language="javascript" type="text/javascript">
    $(".btnsure").click(function(){
        $("input").each(function(){
            vals = $(this).val();
            $(this).parent('td').text(vals);
            if(vals == ''){
                $(this).parent('td').remove();
            }
        });
    });
    var LODOP;
    function print_view(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('foc').innerHTML);
        LODOP.PREVIEW();
    }
    function print(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('foc').innerHTML);
        LODOP.PRINT();
    }
</script>