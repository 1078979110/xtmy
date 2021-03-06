<div class="panel panel-default">
  <div class="panel-heading">{{$tabletitle[0]}}</div>
  <div class="panel-body"  >
	  <div class="horizontal" id="table">
    <table width="680" style="font-size:10px;word-break:break-all; word-wrap:break-word; width: 680px" cellpadding="0" cellspacing="0">
    	<thead>
    		<tr style="line-height: 20px; height: 20px"><td colspan="14" align="center" style="font-size: 16px; font-weight:bold">{{$tabletitle[0]}}</td></tr>
    		<tr style="line-height: 20px; height: 20px"><td colspan="7"><span class="pull-left">{{$tabletitle[1]}}</span><span class="pull-left"><input type="text" class="form-control orderid" value="{{$orderinfo->orderid}}"></span> </td>
				<td colspan="7"><span class="pull-left">{{$tabletitle[2]}}</span> <span class="pull-left"><input type="text" class="form-control" id="orderdate" value="{{date('Y.m.d', strtotime($orderinfo->created_at))}}"></span> </td></tr>
    		<tr style="line-height: 20px; height: 20px"><td colspan="7">{{$tabletitle[3]}}</td><td colspan="7">{{$tabletitle[4]}}</td></tr>
    		<tr style="line-height: 15px; height: 15px; padding: 0">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width: 20px" width="20" align="center">{{$datatitle[0]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 50px" width="50" align="center">{{$datatitle[1]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 130px" width="130" align="center">{{$datatitle[2]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 50px" width="30" align="center">{{$datatitle[3]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 30px" width="30" align="center">{{$datatitle[4]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 130px" width="130" align="center">{{$datatitle[5]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 30px" width="30" align="center">{{$datatitle[6]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 20px" width="30" align="center">{{$datatitle[7]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 20px" width="30" align="center">{{$datatitle[8]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 30px" width="30" align="center">{{$datatitle[9]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 30px" width="30" align="center">{{$datatitle[10]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 56px" width="56" align="center">{{$datatitle[11]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 40px" width="40" align="center">{{$datatitle[12]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width: 30px" width="30" align="center">{{$datatitle[13]}}</td>
    		</tr>
		</thead>
		<tbody>
    		@foreach($lists as $key => $list)
    		<tr style="line-height: 15px; height: 15px; padding: 0; margin: 0">
    			<td style="word-break:break-all; word-wrap:break-word;border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" width="20" align="center">{{$key+1}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="50" align="center"><input type="text" value="" class="form-control nnn"></td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="130" align="center">{{$list['medicinal']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['medicinalnum']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['boxformat']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="130" align="center">{{$list['manufactur']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['num']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['unit']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['price']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['batchnumber']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30" align="center">{{$list['invalidate']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="56" align="center">{{$list['registnum']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="40"align="center">{{$list['registivalidate']}}</td>
    			<td style="word-break:break-all; word-wrap:break-word;border-right:1px solid #000;border-top:1px solid #000" width="30">{{$list['tips']}}</td>
    		</tr>
    		@endforeach
		</tbody>
		<tfoot>
    		<tr style="line-height: 20px; height: 20px">
    			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[5]}}</td>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[6]}}</td>
    			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[7]}}</td>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[8]}}</td>
    		</tr>
		</tfoot>
    </table>
	  </div>
	  @if(!empty($gift))
	  <div class="horizontal" style="margin-top: 10px">
		  <div class="form-group" id="template_forfinance" style="margin:0; padding:0">
			  <table style="font-size:10px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr style="line-height: 20px; height: 20px"><td colspan="10" align="center" style="font-size: 16px; font-weight:bold">赠品信息</td></tr>
				  <tr style="line-height: 20px; height: 20px"><td colspan="10"><span class="pull-left">{{$tabletitle[1]}}</span><span class="pull-left"><input type="text" class="form-control orderid" value="{{$orderinfo->orderid}}"></span></td></tr>
				  <tr style="line-height: 20px; height: 20px">
					  <td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="3">名称</td>
					  <td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">产品货号</td>
					  <td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">数量</td>
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center" colspan="3">来源</td>
				  </tr>
				  @foreach($gift as $key=>$val)
					  <tr style="line-height: 20px; height: 20px">
						  <td style="border-left:1px solid #000;border-top:1px solid #000; border-bottom: 1px solid #000" align="center" colspan="3">{{$val['medicinal']}}</td>
						  <td style="border-left:1px solid #000;border-top:1px solid #000; border-bottom: 1px solid #000" align="center" colspan="2">{{$val['medicinalnum']}}</td>
						  <td style="border-left:1px solid #000;border-top:1px solid #000; border-bottom: 1px solid #000" align="center" colspan="2">{{$val['num']}}</td>
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; border-bottom: 1px solid #000" align="center" colspan="3">{{$val['origin']}}</td>
					  </tr>
				  @endforeach
				  </tbody>
			  </table>
		  </div>
	  </div>
		  @endif
  </div>
  <div class="panel-footer">
	  <button type="button" class="btn btn-primary" onclick="print_view('table');">预览随货同行单</button>
	  <button type="button" class="btn btn-primary" onclick="print('table');">打印随货同行单</button>
	  <button type="button" class="btn btn-primary expert" onclick="getTable('table')">导出</button>
	  <button type="button" class="btn btn-success btnsure">确定</button>
	  @if(!empty($gift))
	  <button type="button" class="btn btn-info" onclick="print_view('template_forfinance');">预览赠品单</button>
	  <button type="button" class="btn btn-info" onclick="print('template_forfinance');">打印赠品单</button>
		  @endif
  </div>
</div>
<script language="javascript" src="/js/LodopFuncs.js"></script>
<script language="javascript" src="/js/dist/xlsx.full.min.js"></script>
<script language="javascript" type="text/javascript">
var LODOP;
var isnull= false;
var data = {!! $jsondata  !!};
function print_view(temp){
    LODOP=getLodop();
    LODOP.PRINT_INIT();
    //LODOP.SET_PRINT_PAGESIZE(1,'241mm','93mm','');
	LODOP.ADD_PRINT_TABLE(10,48,"100%",'100%',document.getElementById(temp).innerHTML);
	LODOP.PREVIEW();
}
function print(temp){
    LODOP=getLodop();
    LODOP.PRINT_INIT();
    //LODOP.SET_PRINT_PAGESIZE(1,'241mm','93mm','');
    LODOP.ADD_PRINT_TABLE(10,48,"100%",'100%',document.getElementById(temp).innerHTML);
	LODOP.PRINT();
}

$(".btnsure").click(function(){
    orderid = $(".orderid").val();
    $(".orderid").parent().text(orderid);
    orderdate = $("#orderdate").val();
    $("#orderdate").parent().text(orderdate);
    expressid = $("#expressid").val();
    $("#expressid").parent().text(expressid);
    $(".nnn").each(function(index,item){
        text = $(this).val();
        $(this).parent().text(text);
    })
});
function getTable(table){
    var exceltitle = '{{$tabletitle[4]}}';
    var sheet  = XLSX.utils.table_to_sheet($('#'+table)[0]);
    openDownloadDialog(sheet2blob(sheet), exceltitle+'.xls');
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