<div class="panel panel-default">
  <div class="panel-heading">{{$tabletitle[0]}}</div>
  <div class="panel-body" id="table" >
    <table  style="font-size:12px" cellpadding="0" cellspacing="0">
    	<tbody>
    		<tr style="height: 30px"><td colspan="14" align="center" style="font-size: 16px; font-weight:bold">{{$tabletitle[0]}}</td></tr>
    		<tr style="height: 30px"><td colspan="5">{{$tabletitle[1]}}</td><td colspan="4">{{$tabletitle[2]}}</td><td colspan="5">{{$tabletitle[3]}}</td></tr>
    		<tr style="height: 30px;line-height: 20px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:130px" align="center">{{$datatitle[0]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[1]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[2]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[3]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[5]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[6]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[7]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[8]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[9]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:40px" align="center">{{$datatitle[10]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[11]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:56px" align="center">{{$datatitle[12]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:180px" align="center">{{$datatitle[13]}}</td>
    		</tr>
    		@foreach($lists as $key => $list)
    		<tr style="height: 30px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['specification']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['boxformat']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['novirus']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['makedate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registivalidate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" >{{$list['manufactur']}}</td>
    		</tr>
    		@endforeach
    		<tr style="line-height: 30px; height: 30px"><td style="border:1px solid #000" colspan="14" >{{$tabletitle[4]}}{{$total}}元</td></tr>
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
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.4cm",'100%',document.getElementById('table').innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PREVIEW();
		}
	}
}
function print(){
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.4cm",'100%',document.getElementById('table').innerHTML);
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