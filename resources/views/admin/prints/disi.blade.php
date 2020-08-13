<div class="panel panel-default">
  <div class="panel-heading">{{$tabletitle[0]}}</div>
  <div class="panel-body" id="table" >
    <table  style="font-size:12px" cellpadding="0" cellspacing="0">
    	<tbody>
    		<tr><td colspan="12" align="center" style="font-size: 14px; font-weight:bold">{{$tabletitle[0]}}</td></tr>
    		<tr><td colspan="4">{{$tabletitle[1]}}</td><td colspan="4">{{$tabletitle[2]}}</td><td colspan="4">{{$tabletitle[3]}}</td></tr>
    		<tr>
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[0]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:200px">{{$datatitle[1]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:250px">{{$datatitle[2]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:100px">{{$datatitle[3]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[4]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[5]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[6]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[7]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[8]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[9]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:100px">{{$datatitle[10]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[11]}}</td>
    		</tr>
    		@foreach($lists as $key => $list)
    		<tr>
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinalnum']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinal']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['manufactur']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['specification']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['unit']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['num']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['price']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['prices']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['batchnumber']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['novirus']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['invalidate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['depart']}}</td>
    		</tr>
    		@endforeach
    		<tr><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="12" >{{$tabletitle[4]}}{{$total}}</td></tr>
    		<tr>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[5]}}</td>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[6]}}</td>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[7]}}</td>
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
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.9cm",'100%',document.getElementById('table').innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PREVIEW();
		}
	}
}
function print(){
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.9cm",'100%',document.getElementById('table').innerHTML);
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