<script language="javascript" src="/js/LodopFuncs.js"></script>
<script language="javascript" src="/js/dist/xlsx.full.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">{{$title}}</div>
    <div class="panel-body" id="table" >
        <div class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2">销售订单/FOC 申请表</label>
                <div class="col-sm-8" id="foc">
                    <table style="font-size: 14px; width: 500px" id="foctable" cellpadding="0" cellspacing="0">
                        <tr><td colspan="7" align="center" style="font-size: 16px; font-weight: bold">销售订单/FOC 申请表</td></tr>
                        <tr><td colspan="4">{{$focname[0]}}</td><td colspan="3">{{$focname[1]}}</td></tr>
                        <tr><td colspan="7">{{$focname[2]}}</td></tr>
                        <tr>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][0]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtitle'][1]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000; width: 50px">{{$salefoc['listtitle'][2]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;width: 50px">{{$salefoc['listtitle'][3]}}</td>
                            <td align="center" style="border-top: 1px solid #000;border-left:1px solid #000;width: 50px">{{$salefoc['listtitle'][4]}}</td>
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
                            <td colspan="6" style="border-top: 1px solid #000;border-left:1px solid #000;">{{$salefoc['listtotal'][0]}}：<span id="totals">{{$total}}</span> 元</td>
                            <td style="border-top: 1px solid #000;border-left:1px solid #000; border-right: 1px solid #000"></td>
                        </tr>
                        <tr><td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000; height:10px;" colspan="7"></td></tr>
                        <tr>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000;">{{$salefoc['foctitle'][0]}}</td>
                            <td colspan="5" style="border-top: 1px solid #000; border-left:1px solid #000;">{{$salefoc['foctitle'][1]}}</td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000">{{$salefoc['foctitle'][3]}}</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td colspan="5" style="border-top: 1px solid #000; border-left:1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control focprice" ></td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td colspan="5" style="border-top: 1px solid #000; border-left:1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control focprice" ></td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td colspan="5" style="border-top: 1px solid #000; border-left:1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control" ></td>
                            <td style="border-top: 1px solid #000; border-left:1px solid #000; border-right: 1px solid #000;height: 35px;"><input type="text" placeholder="请输入内容" class="form-control focprice" ></td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000;border-left:1px solid #000; border-bottom: 1px solid #000">{{$salefoc['foctotal'][0]}}</td>
                            <td class="foctotal" colspan="6" style="border-top: 1px solid #000;border-left:1px solid #000; border-bottom: 1px solid #000;border-right: 1px solid #000;height: 35px;"><input type="text" class="form-control foctotal" value="0.00"> </td>
                        </tr>
                        <tr><td colspan="7" style="height: 20px"></td></tr>
                        <tr><td>特别说明</td><td colspan="6"><textarea placeholder="特别说明" id="explain" class="form-control" style="min-height: 50px; margin-top: 20px"></textarea></td></tr>
                        <tr><td colspan="7" style="height: 20px"></td></tr>
                        <tr>
                            <td colspan="7" style="height: 100px;position: relative">客户盖章：<img src="/images/yinzhang.png" class="yinzhang"></td>
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
                <button type="button" class="btn btn-primary expert" onclick="getTable('foctable')">导出</button>
                <button type="button" class="btn btn-info" onclick="print_view();">预览</button>
                <button type="button" class="btn btn-info" onclick="print();">打印</button>
            </div>
        </div>
    </div>
</div>
<style>
    .yinzhang{
        width: 130px;
        position: absolute;
        left: 0;
        top: -12px;
        z-index: 2;
    }
</style>
<script language="javascript" type="text/javascript">
    $(".btnsure").click(function(){
        var totals = $("#totals").text();
        $("input").each(function(){
            vals = $(this).val();
            if($(this).hasClass('focprice')){
                totals = totals-vals;
                $(this).parent('td').text(vals+'元');
            }else{
                $(this).parent('td').text(vals);
            }

            if(vals == ''){
                $(this).parent('td').remove();
            }
        });
        explain = $("#explain").val();
        $("#explain").parent('td').text(explain);
        $(".foctotal").text(totals+'元');
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

    function getTable(table){
        title = '{{$title}}';
        var sheet  = XLSX.utils.table_to_sheet($('#'+table)[0]);
        openDownloadDialog(sheet2blob(sheet), title + '.xls');
    }

    function openDownloadDialog(url, saveName)
    {
        if(typeof url == 'object' && url instanceof Blob)
        {
            url = URL.createObjectURL(url); // 创建blob地址
        }
        var aLink = document.createElement('a');
        aLink.href = url;
        aLink.download = saveName || ''; // HTML5新增的属性，指定保存文件名，可以不要后缀，注意，file:///模式下不会生效
        var event;
        if(window.MouseEvent) event = new MouseEvent('click');
        else
        {
            event = document.createEvent('MouseEvents');
            event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        }
        aLink.dispatchEvent(event);
    }
    function sheet2blob(sheet, sheetName) {
        sheetName = sheetName || 'sheet1';
        var workbook = {
            SheetNames: [sheetName],
            Sheets: {}
        };
        workbook.Sheets[sheetName] = sheet;
        // 生成excel的配置项
        var wopts = {
            bookType: 'xlsx', // 要生成的文件类型
            bookSST: false, // 是否生成Shared String Table，官方解释是，如果开启生成速度会下降，但在低版本IOS设备上有更好的兼容性
            type: 'binary'
        };
        var wbout = XLSX.write(workbook, wopts);
        var blob = new Blob([s2ab(wbout)], {type:"application/octet-stream"});
        // 字符串转ArrayBuffer
        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
        return blob;
    }
</script>