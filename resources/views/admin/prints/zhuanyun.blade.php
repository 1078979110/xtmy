<script language="javascript" src="/js/LodopFuncs.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">{{$title}}</div>
    <div class="panel-body" id="table" >
        <div class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2">转运证明</label>
                <div class="col-sm-8" id="zhuanyun">
                    <table style="font-size: 14px; width: 500px" cellpadding="0"; cellspacing="0">
                        <tr><td colspan="4" align="center">转运证明</td></tr>
                        <tr><td colspan="4" style="text-indent: 2em;padding: 20px;"><textarea placeholder="请输入转运声明内容" id="header" class="form-control" style="min-height: 100px"></textarea></td></tr>
                        <tr>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$zhuanyun['title'][0]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$zhuanyun['title'][1]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$zhuanyun['title'][2]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000">{{$zhuanyun['title'][3]}}</td>
                        </tr>
                        @foreach($lists as $key=>$list)
                            <tr>
                                <td align="center" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$list['medicinalnum']}}</td>
                                <td align="center" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$list['medicinal']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$list['unit']}}</td>
                                <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000">{{$list['num']}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" style="border-top: 1px solid #000;"></td><td colspan="2" style="border-top: 1px solid #000; padding-top: 20px">{{$zhuanyun['footer']}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td><td style="">{{$zhuanyun['date']}}</td>
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
                <button type="button" class="btn btn-info" onclick="print_view();">预览转运证明</button>
                <button type="button" class="btn btn-info" onclick="print();">打印转运证明</button>
            </div>
        </div>
    </div>
</div>

<script language="javascript" type="text/javascript">
    var LODOP;
    function print_view(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('zhuanyun').innerHTML);
        LODOP.PREVIEW();
    }
    function print(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('zhuanyun').innerHTML);
        LODOP.PRINT();
    }
    $(".btnsure").click(function(){
        textvalue = $("#header").val();
        $("#header").parent('td').text(textvalue);
    });
</script>