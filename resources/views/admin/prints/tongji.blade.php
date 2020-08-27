<div class="panel panel-default">
  <div class="panel-heading">{{$tabletitle[0]}}</div>
  <div class="panel-body" id="table">
    <table  style="font-size:12px" cellpadding="0" cellspacing="0">
    	<tbody>
			<tr style="line-height: 30px; height: 30px"><td colspan="11" align="center" style="font-size: 16px; font-weight:bold">{{$tabletitle[0]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px"><td colspan="5">{{$tabletitle[1]}}</td><td colspan="5">{{$tabletitle[2]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px"><td colspan="11">{{$tabletitle[3]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px"><td colspan="3">{{$tabletitle[4]}}</td><td colspan="3">{{$tabletitle[5]}}</td><td colspan="5">{{$tabletitle[6]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[0]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:150px" align="center">{{$datatitle[1]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[2]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:60px" align="center">{{$datatitle[5]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[6]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[7]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[8]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[9]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:250px" align="center">{{$datatitle[10]}}</td>
    		</tr>
    		@foreach($lists as $key => $list)
    		<tr style="line-height: 30px; height: 30px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['specification']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufactur']}}</td>

    		</tr>
    		@endforeach
    		<tr style="line-height: 30px; height: 30px"><td colspan="11" style="border-top: 1px solid #000;border-left:1px solid #000;border-right:1px solid #000;">{{$tabletitle[7]}}{{$total}}</td></tr>
    		<tr style="line-height: 30px; height: 30px">
    			<td colspan="11" style="border-top: 1px solid #000">{{$tabletitle[8]}}</td>
    		</tr>
    	</tbody>
    </table>
  </div>
  <div class="panel-footer">
  	<button type="button" class="btn btn-primary" onclick="print_view();">打印预览</button>
  	<button type="button" class="btn btn-info" onclick="print();">打印</button>
  </div>
</div>
<script language="javascript" src="/js/LodopFuncs.js"></script>
<script language="javascript" type="text/javascript">
var LODOP;
var isnull= false;
var data = {!! $jsondata  !!};
function print_view(){
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('table').innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PREVIEW();
		}
	}
}
function print(){
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('table').innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PRINT();
		}
	}
}

function check_isnull(data){
	for(var i in data){
		for(var j in data[i]){
    		if(data[i][j].indexOf("") != -1){
    			isnull = true;
    			return false;
    		}
		}
	}
}





</script>
