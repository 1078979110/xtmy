<div class="panel panel-default">
  <div class="panel-heading">{{$tabletitle[0]}}</div>
  <div class="panel-body" >
	  <div class="horizontal" id="table">
    <table  style="font-size:12px" cellpadding="0" cellspacing="0">
    	<tbody>
    		<tr style="line-height: 30px; height: 30px"><td colspan="10" align="center" style="font-size: 16px; font-weight:bold">{{$tabletitle[0]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px"><td colspan="2">{{$tabletitle[1]}}</td><td colspan="2">{{$tabletitle[2]}}</td><td colspan="2">{{$tabletitle[3]}}</td><td colspan="2">{{$tabletitle[4]}}</td><td colspan="2">{{$tabletitle[5]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px"><td colspan="5">{{$tabletitle[6]}}</td><td colspan="5">{{$tabletitle[7]}}</td></tr>
    		<tr style="line-height: 30px; height: 30px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:150px" align="center">{{$datatitle[0]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[1]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[2]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[5]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[6]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[7]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$datatitle[8]}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000;" align="center">{{$datatitle[9]}}</td>
    		</tr>
    		@foreach($lists as $key => $list)
    		<tr style="line-height: 30px; height: 30px">
    			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['specification']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufactur']}}</td>
    			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['novirus']}}</td>
    			
    		</tr>
    		@endforeach
    		<tr style="line-height: 30px; height: 30px"><td colspan="10" style="border-top: 1px solid #000;border-left:1px solid #000;border-right:1px solid #000;">{{$tabletitle[8]}}{{$total}}</td></tr>
			<tr style="line-height: 30px; height: 30px"><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="10" align="center">赠品信息</td></tr>
			<tr style="line-height: 30px; height: 30px">
				<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="3">名称</td>
				<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">产品货号</td>
				<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">数量</td>
				<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center" colspan="3">来源</td>
			</tr>
			@foreach($gift as $key=>$val)
				<tr style="line-height: 30px; height: 30px">
					<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="3">{{$val['medicinal']}}</td>
					<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">{{$val['medicinalnum']}}</td>
					<td style="border-left:1px solid #000;border-top:1px solid #000" align="center" colspan="2">{{$val['num']}}</td>
					<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center" colspan="3">{{$val['origin']}}</td>
				</tr>
			@endforeach
    		<tr style="line-height: 30px; height: 30px">
    			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[9]}}</td>
    			<td colspan="4" style="border-top: 1px solid #000">{{$tabletitle[10]}}</td>
    			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[11]}}</td>
    		</tr>
    	</tbody>
    </table>
	  </div>
	  {{--<div class="horizontal">
		  <div class="form-group" id="template_forfinance" style="margin:0; padding:0">
			  <table style="font-size:14px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr><td colspan="6" align="center" style="font-size: 16px; font-weight:bold">{{$financename[0]}}</td></tr>
				  <tr><td colspan="3" align="left">{{$financename[1]}}</td><td colspan="3" align="right">{{$financename[2]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$financedatatitle[0]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[1]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[2]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[3]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[4]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[5]}}</td>
				  </tr>
				  @foreach($lists as $key => $list)
					  <tr style="line-height: 30px; height: 30px">
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['specification']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center"></td>
					  </tr>
				  @endforeach

				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="3" style="border-top: 1px solid #000">{{$financename[3]}}</td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$financename[4]}}<span tindex="5" tdata="SubSum" format="###,###,###,###,###.00">##########元</span></td>
				  </tr>
				  </tbody>
			  </table>
		  </div>
	  </div>--}}
  </div>
  <div class="panel-footer">
	  <button type="button" class="btn btn-primary" onclick="print_view('table');">预览随货同行单</button>
	  <button type="button" class="btn btn-primary" onclick="print('table');">打印随货同行单</button>
	  {{--<button type="button" class="btn btn-info" onclick="print_view('template_forfinance');">预览出库单</button>
	  <button type="button" class="btn btn-info" onclick="print('template_forfinance');">打印出库单</button>--}}
  </div>
</div>
<script language="javascript" src="/js/LodopFuncs.js"></script>
<script language="javascript" type="text/javascript">
var LODOP;
var isnull= false;
var data = {!! $jsondata  !!};
function print_view(temp){
    LODOP=getLodop();
    LODOP.PRINT_INIT();
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById(temp).innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PREVIEW();
		}
	}
}
function print(temp){
    LODOP=getLodop();
    LODOP.PRINT_INIT();
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById(temp).innerHTML);
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
