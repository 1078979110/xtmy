<script language="javascript" src="/js/LodopFuncs.js"></script>
<script language="javascript" src="/js/dist/xlsx.full.min.js"></script>
<div class="panel panel-default">
    <div class="panel-heading">批发专用</div>
    <div class="panel-body" id="table" >
        <table style="font-size:14px;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr><td colspan="7" align="center" style="font-size: 16px; font-weight:bold">批发专用</td></tr>
            <tr><td colspan="7" align="right">订单号：{{$orderinfo->orderid}}</td></tr>
            <tr style="line-height: 30px; height: 30px">
                <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">序号</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">药品名称</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">货号</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">单位</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">单价</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">数量</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">小计</td>
            </tr>
            @foreach($medicinals as $key => $list)
            <tr style="line-height: 30px; height: 30px">
                <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$key+1}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
                <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
            </tr>
            @endforeach

            <tr style="line-height: 30px; height: 30px">
                <td colspan="4" style="border-top: 1px solid #000">总计：{{$orderinfo->totalprice}}</td>
                <td colspan="3" style="border-top: 1px solid #000">本页小计<span tindex="7"  tdata="SubSum" format="###,###,###,###,###.00">##########元</span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <label class="control-label col-sm-2"></label>
        <div class="form-group">
            <div class="button-group col-sm offset-2">
                <button type="button" class="btn btn-info" onclick="print_view();">预览</button>
                <button type="button" class="btn btn-info" onclick="print();">打印</button>
                <button type="button" class="btn btn-info" onclick="getTable();">导出</button>
            </div>
        </div>
    </div>
</div>

<script language="javascript" type="text/javascript">
    var LODOP;
    function print_view(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('table').innerHTML);
        LODOP.PREVIEW();
    }
    function print(){
        LODOP=getLodop();
        LODOP.PRINT_INIT();
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('table').innerHTML);
        LODOP.PRINT();
    }
    function getTable(){
        var sheet  = XLSX.utils.table_to_sheet($("#table")[0]);
        openDownloadDialog(sheet2blob(sheet),  '批发专用-'+ {{$orderinfo->orderid}}+'.xls');
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