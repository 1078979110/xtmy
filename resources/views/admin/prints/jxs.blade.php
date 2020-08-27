<script language="javascript" src="/js/LodopFuncs.js"></script>
<div class="panel panel-default">
  <div class="panel-heading">{{$title}}</div>
  <div class="panel-body" id="table" >
      <div class="form-horizontal">
      	<div class="form-group">
      		<label class="col-sm-2 control-label">选择模板</label>
      		<div class="col-sm-4">
      			<select name="template" class="form-control">
      				<option value="1" selected="selected">模板一</option>
      				<option value="2">模板二</option>
      				<option value="3">模板三</option>
      				<option value="4">模板四</option>
      			</select>
      		</div>
      	</div>
      	<div class="form-group template " id="template1" style="margin: 0; padding:0; ">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:12px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr><td colspan="8" align="center" style="font-size: 14px; font-weight:bold">{{$title}}</td></tr>
      				<tr><td colspan="4" align="left">{{$tabletitle[0][0]}}</td><td colspan="4" align="right">{{$tabletitle[0][1]}}</td></tr>
      				<tr><td colspan="8" align="right">{{$tabletitle[0][2]}}</td></tr>
      				<tr>
      					<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[0][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:200px">{{$datatitle[0][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[0][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[0][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[0][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[0][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[0][6]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[0][7]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$key+1}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['specification']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['batchnumber']}}</td>           			
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['invalidate']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['registnum']}}</td>
            		</tr>
            		@endforeach
            		<tr><td style="border-top:1px solid #000" colspan="6" align="left">{{$tabletitle[0][3]}}</td><td style="border-top:1px solid #000" colspan="2" align="left">{{$tabletitle[0][4]}}</td></tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template2" style="display: none; margin:5px; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:12px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr><td colspan="7" align="center" style="font-size: 14px; font-weight:bold">{{$title}}</td></tr>
      				<tr><td colspan="4" align="left">{{$tabletitle[1][0]}}</td><td colspan="3" align="right">{{$tabletitle[1][1]}}</td></tr>
      				<tr><td colspan="4" align="left">{{$tabletitle[1][2]}}</td><td colspan="3" align="right">{{$tabletitle[1][3]}}</td></tr>
      				<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px">{{$datatitle[1][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[1][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[1][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[1][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[1][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[1][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[1][6]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['specification']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['price']}}</td>           			
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['invalidate']}}</td>
            		</tr>
            		@endforeach
            		<tr><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="7" align="left">{{$tabletitle[1][4]}}</td></tr>
            		<tr>
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[1][5]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[1][6]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[1][7]}}</td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template3" style="display: none;margin:0; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:12px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr><td colspan="6" align="center" style="font-size: 14px; font-weight:bold">{{$title}}</td></tr>
      				<tr><td colspan="3" align="left">{{$tabletitle[2][0]}}</td><td colspan="3" align="right">{{$tabletitle[2][1]}}</td></tr>
      				<tr><td colspan="3" align="left">{{$tabletitle[2][2]}}</td><td colspan="3" align="right">{{$tabletitle[2][3]}}</td></tr>
      				<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px">{{$datatitle[2][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[2][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[2][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[2][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[2][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[2][5]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['specification']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['num']}}</td>			
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['invalidate']}}</td> 
            		</tr>
            		@endforeach
            		<tr><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="6" align="left">{{$tabletitle[2][4]}}</td></tr>
            		<tr>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][5]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][6]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][7]}}</td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template4" style="display: none;margin:0; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:12px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr><td colspan="10" align="center" style="font-size: 14px; font-weight:bold">{{$title}}</td></tr>
      				<tr><td colspan="5" align="left">{{$tabletitle[3][0]}}</td><td colspan="5" align="right">{{$tabletitle[3][1]}}</td></tr>
      				<tr><td colspan="5" align="left">{{$tabletitle[3][2]}}</td><td colspan="5" align="right">{{$tabletitle[3][3]}}</td></tr>
      				<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:120px">{{$datatitle[3][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:220px">{{$datatitle[3][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[3][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:30px">{{$datatitle[3][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[3][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[3][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px">{{$datatitle[3][6]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px">{{$datatitle[3][7]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:60px">{{$datatitle[3][8]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:70px">{{$datatitle[3][9]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['manufactur']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['specification']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['price']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['prices']}}</td>			
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['registnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000">{{$list['invalidate']}}</td>  
            		</tr>
            		@endforeach
            		<tr>
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="4" align="left">{{$tabletitle[3][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" colspan="3" align="left">{{$tabletitle[3][5]}}元</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" colspan="3" align="left">{{$tabletitle[3][6]}}<span tindex="7" tdata="SubSum" format="###,###,###,###,###.00">##########元</span></td>
            		</tr>
            		<tr>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[3][7]}}</td>
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[3][8]}}</td>
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[3][9]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000"><span tdata="PageNO">###</span>/<span tdata="PageCount" >###</span></td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
  	</div>
  </div>
  <div class="panel-footer">
  	<label class="control-label col-sm-2"></label>
  	<div class="form-group">
  		<div class="button-group col-sm offset-2">
  			<button type="button" class="btn btn-primary" onclick="print_view();">打印预览</button>
  			<button type="button" class="btn btn-info" onclick="print();">打印</button>
  		</div>
  	</div>
  </div>
</div>

<script language="javascript" type="text/javascript">
var tid = 1;
var LODOP;
var isnull= false;
var data = {!! $jsondata  !!};
$("select[name='template']").change(function(){
	tid = $(this).val();
	console.log(tid);
	$(".template").hide();
	$("#template"+tid).show();
});
function print_view(){
	LODOP=getLodop(); 
	LODOP.PRINT_INIT();
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.9cm",'100%',document.getElementById('template'+tid).innerHTML);
	check_isnull(data);
	if(isnull == true){
		isprint = confirm('该出货单中存在未填写的项,确定打印？');
		if(isprint == true){
			LODOP.PREVIEW();
		}
	}
	
}
function print(){
	LODOP=getLodop(); 
	LODOP.PRINT_INIT();
	LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.9cm",'100%',document.getElementById('template'+tid).innerHTML);
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