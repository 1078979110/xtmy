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
					<option value="5">模板五</option>
					<option value="6">模板六</option>
					<option value="7">模板七（出库单）</option>
					<option value="8">模板八</option>
      			</select>
      		</div>
      	</div>
      	<div class="form-group template " id="template1" style="margin: 0; padding:0; ">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:14px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr style="line-height: 30px; height: 30px"><td colspan="8" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
      				<tr style="line-height: 30px; height: 30px"><td colspan="4" align="left" class="title-excel-1"><span class="pull-left">{{$tabletitle[0][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-1" value=""></span></td><td colspan="4" align="right">{{$tabletitle[0][1]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px"><td colspan="8" align="right">{{$tabletitle[0][2]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px">
      					<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[0][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$datatitle[0][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[0][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[0][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[0][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[0][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[0][6]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[0][7]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$key+1}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
            		</tr>
            		@endforeach
            		<tr style="line-height: 30px; height: 30px"><td style="border-top:1px solid #000" colspan="6" align="left">{{$tabletitle[0][3]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td><td style="border-top:1px solid #000" colspan="2" align="left">{{$tabletitle[0][4]}}</td></tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template2" style="display: none; margin:5px; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:14px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr style="line-height: 30px; height: 30px"><td colspan="7" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
      				<tr style="line-height: 30px; height: 30px"><td colspan="4" align="left" class="title-excel-2"><span class="pull-left">{{$tabletitle[1][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-2" value=""></span></td><td colspan="3" align="right">{{$tabletitle[1][1]}}</td></tr>
      				<tr><td colspan="4" align="left">{{$tabletitle[1][2]}}</td><td colspan="3" align="right">{{$tabletitle[1][3]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$datatitle[1][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[1][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[1][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[1][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[1][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[1][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[1][6]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
            		</tr>
            		@endforeach
            		<tr style="line-height: 30px; height: 30px"><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="7" align="left">{{$tabletitle[1][4]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td></tr>
            		<tr style="line-height: 30px; height: 30px">
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[1][5]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[1][6]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[1][7]}}</td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template3" style="display: none;margin:0; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:14px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr><td colspan="6" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
      				<tr><td colspan="3" align="left" class="title-excel-3"><span class="pull-left">{{$tabletitle[2][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-3" value=""></span></td><td colspan="3" align="right">{{$tabletitle[2][1]}}</td></tr>
      				<tr><td colspan="3" align="left">{{$tabletitle[2][2]}}</td><td colspan="3" align="right">{{$tabletitle[2][3]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$datatitle[2][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[2][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[2][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[2][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[2][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[2][5]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
            		</tr>
            		@endforeach
            		<tr style="line-height: 30px; height: 30px"><td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="6" align="left">{{$tabletitle[2][4]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td></tr>
            		<tr style="line-height: 30px; height: 30px">
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][5]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][6]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[2][7]}}</td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
      	<div class="form-group col-sm-offset-2 template" id="template4" style="display: none;margin:0; padding:0">
      		<label class="col-sm-2 control-label">模板</label>
      		<table style="font-size:14px;" cellpadding="0" cellspacing="0">
      			<tbody>
      				<tr style="line-height: 30px; height: 30px"><td colspan="10" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
      				<tr style="line-height: 30px; height: 30px"><td colspan="5" align="left" class="title-excel-4"><span class="pull-left">{{$tabletitle[3][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-4" value=""></span></td><td colspan="5" align="right">{{$tabletitle[3][1]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px"><td colspan="5" align="left">{{$tabletitle[3][2]}}</td><td colspan="5" align="right">{{$tabletitle[3][3]}}</td></tr>
      				<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[3][0]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[3][1]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3][2]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[3][3]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3][4]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3][5]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[3][6]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[3][7]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:60px" align="center">{{$datatitle[3][8]}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000; width:70px" align="center">{{$datatitle[3][9]}}</td>
      				</tr>
      				@foreach($lists as $key => $list)
            		<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufactur']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
            		</tr>
            		@endforeach
            		<tr style="line-height: 30px; height: 30px">
            			<td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="4" align="left">{{$tabletitle[3][4]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" colspan="3" align="left">{{$tabletitle[3][5]}}{{$total}}</td>
            			<td style="border-right:1px solid #000;border-top:1px solid #000" colspan="3" align="left">{{$tabletitle[3][6]}}<span tindex="7" tdata="SubSum" format="###,###,###,###,###.00">##########元</span></td>
            		</tr>
            		<tr style="line-height: 30px; height: 30px">
            			<td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[3][7]}}</td>
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[3][8]}}</td>
            			<td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[3][9]}}</td>
            			<td colspan="2" style="border-top: 1px solid #000"><span tdata="PageNO">###</span>/<span tdata="PageCount" >###</span></td>
            		</tr>
      			</tbody>
      		</table>
      	</div>
		  <div class="form-group col-sm-offset-2 template" id="template5" style="display: none;margin:0; padding:0">
			  <label class="col-sm-2 control-label">模板</label>
			  <table style="font-size:14px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr style="line-height: 30px; height: 30px"><td colspan="10" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
				  <tr style="line-height: 30px; height: 30px"><td colspan="5" align="left" class="title-excel-5"><span class="pull-left">{{$tabletitle[4][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-5" value=""></span></td><td colspan="5" align="right">{{$tabletitle[4][1]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px"><td colspan="5" align="left">{{$tabletitle[4][2]}}</td><td colspan="5" align="right">{{$tabletitle[4][3]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[4][0]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[4][1]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4][2]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[4][3]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4][4]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4][5]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[4][6]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[4][7]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:60px" align="center">{{$datatitle[4][8]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:70px" align="center">{{$datatitle[4][9]}}</td>
				  </tr>
				  @foreach($lists as $key => $list)
					  <tr style="line-height: 30px; height: 30px">
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufactur']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufacturinglicense']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['makedate']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
					  </tr>
				  @endforeach
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="10" align="left">{{$tabletitle[4][4]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td>
				  </tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[4][6]}}</td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[4][7]}}</td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[4][8]}}</td>
					  <td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[4][9]}}</td>
				  </tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="4">{{$tabletitle[4][10]}}</td>
					  <td colspan="4">{{$tabletitle[4][11]}}</td>
					  <td colspan="2">{{$tabletitle[4][12]}}</td>
				  </tr>
				  </tbody>
			  </table>
		  </div>
		  <div class="form-group col-sm-offset-2 template" id="template6" style="display: none;margin:0; padding:0">
			  <label class="col-sm-2 control-label">模板</label>
			  <table style="font-size:14px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr style="line-height: 30px; height: 30px"><td colspan="10" align="center" style="font-size: 16px; font-weight:bold">{{$title}}</td></tr>
				  <tr style="line-height: 30px; height: 30px"><td colspan="5" align="left" class="title-excel-6"><span class="pull-left">{{$tabletitle[5][0]}}</span> <span class="pull-left"><input type="text" class="form-control jxsname-6" value=""></span></td><td colspan="5" align="right">{{$tabletitle[5][1]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px"><td colspan="5" align="left"><span class="pull-left">{{$tabletitle[5][2]}}</span></span> <span class="pull-left saddress"><input type="text" class="form-control iaddress" value=""></span></td><td colspan="5" align="right">{{$tabletitle[5][3]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[5][0]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:120px" align="center">{{$datatitle[5][1]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[5][2]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:30px" align="center">{{$datatitle[5][3]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[5][4]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[5][5]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$datatitle[5][6]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$datatitle[5][7]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:60px" align="center">{{$datatitle[5][8]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:70px" align="center">{{$datatitle[5][9]}}</td>
				  </tr>
				  @foreach($lists as $key => $list)
					  <tr style="line-height: 30px; height: 30px">
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufactur']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['manufacturinglicense']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['batchnumber']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['registnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['makedate']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['invalidate']}}</td>
					  </tr>
				  @endforeach
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" colspan="10" align="left">{{$tabletitle[5][4]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td>
					  {{--<td style="border-right:1px solid #000;border-top:1px solid #000" colspan="3" align="left">{{$tabletitle[5][5]}}</td>--}}
				  </tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[5][6]}}</td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[5][7]}}</td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$tabletitle[5][8]}}</td>
					  <td colspan="2" style="border-top: 1px solid #000">{{$tabletitle[5][9]}}</td>
				  </tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="4">{{$tabletitle[5][10]}}</td>
					  <td colspan="4">{{$tabletitle[5][11]}} </td>
					  <td colspan="2">{{$tabletitle[5][12]}}</td>
				  </tr>
				  </tbody>
			  </table>
		  </div>
		  <div class="form-group col-sm-offset-2 template" id="template7" style="display: none;margin:0; padding:0">
			  <label class="col-sm-2 control-label">模板</label>
			  <table style="font-size:14px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr><td colspan="7" align="center" style="font-size: 16px; font-weight:bold">{{$financename[0]}}</td></tr>
				  <tr><td colspan="4" align="left" class="title-excel-7"><span class="pull-left">{{$financename[1]}}</span> <span class="pull-left "><input type="text" class="form-control jxsname-7" value=""></span> </td>
					  <td colspan="3" align="right">{{$financename[2]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$financedatatitle[0]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[1]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[2]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[3]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[4]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[5]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[6]}}</td>
				  </tr>
				  @foreach($lists as $key => $list)
					  <tr style="line-height: 30px; height: 30px">
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['num']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['price']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['prices']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['tips']}}</td>
					  </tr>
				  @endforeach

				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="4" style="border-top: 1px solid #000">{{$financename[3]}}<span class="totalcn" style="display: none">{{$totalcn}}</span></td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$financename[4]}}<span tindex="5" class="totalcn"  tdata="SubSum" format="###,###,###,###,###.00">##########元</span></td>
				  </tr>
				  </tbody>
			  </table>
		  </div>
		  <div class="form-group col-sm-offset-2 template" id="template8" style="display: none;margin:0; padding:0">
			  <label class="col-sm-2 control-label">模板</label>
			  <table style="font-size:14px;" cellpadding="0" cellspacing="0">
				  <tbody>
				  <tr><td colspan="7" align="center" style="font-size: 16px; font-weight:bold">{{$financename[0]}}</td></tr>
				  <tr><td colspan="4" align="left" class="title-excel-8"><span class="pull-left">{{$financename[1]}}</span> <span class="pull-left "><input type="text" class="form-control jxsname-8" value=""></span> </td>
					  <td colspan="3" align="right">{{$financename[2]}}</td></tr>
				  <tr style="line-height: 30px; height: 30px">
					  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000; width:200px" align="center">{{$financedatatitle[0]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[1]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[2]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:50px" align="center">{{$financedatatitle[3]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[4]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[5]}}</td>
					  <td style="border-right:1px solid #000;border-top:1px solid #000; width:80px" align="center">{{$financedatatitle[6]}}</td>
				  </tr>
				  @foreach($lists as $key => $list)
					  <tr style="line-height: 30px; height: 30px">
						  <td style="border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinal']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['medicinalnum']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['unit']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center" class="num">{{$list['num']}}</td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center"><input type="number" value="{{$list['price']}}" class="form-control price"></td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center" class="minitotal"><input type="number" name="minitotal" value="{{$list['prices']}}" class="form-control"></td>
						  <td style="border-right:1px solid #000;border-top:1px solid #000" align="center">{{$list['tips']}}</td>
					  </tr>
				  @endforeach

				  <tr style="line-height: 30px; height: 30px">
					  <td colspan="4" style="border-top: 1px solid #000">{{$financename[3]}}<span class="totalcn big" style="display: none">{{$totalcn}}</span></td>
					  <td colspan="3" style="border-top: 1px solid #000">{{$financename[4]}}<span tindex="5" class="totalcn"  tdata="SubSum" format="###,###,###,###,###">##########元</span></td>
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
  			<button type="button" class="btn btn-primary" onclick="print_view();">预览同行单</button>
  			<button type="button" class="btn btn-primary" onclick="print();">打印同行单</button>
			<button type="button" class="btn btn-success" onclick="togglecn()">显示/隐藏合计（大写）</button>
			<button type="button" class="btn btn-primary expert" onclick="getTable()">导出</button>
			<button type="button" class="btn btn-primary btnsrue">确定</button>
  		</div>
  	</div>
  </div>
</div>
<script language="javascript" src="/js/dist/xlsx.full.min.js"></script>
<script language="javascript" type="text/javascript">
	var jxsname = '';
var tid = 1;
var LODOP;
var isnull= false;
var data = {!! $jsondata  !!};
$("select[name='template']").change(function(){
	tid = $(this).val();
	$(".template").hide();
	$("#template"+tid).show();
});
function print_view(temp){
	LODOP=getLodop(); 
	LODOP.PRINT_INIT();
	if(!temp){
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('template'+tid).innerHTML);
	}else{
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById(temp).innerHTML);
	}
	LODOP.PREVIEW();
}
function print(temp){
	LODOP=getLodop(); 
	LODOP.PRINT_INIT();
    if(!temp){
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById('template'+tid).innerHTML);
    }else{
        LODOP.ADD_PRINT_TABLE(40,10,"RightMargin:0.3cm",'100%',document.getElementById(temp).innerHTML);
    }
	LODOP.PRINT();
}
function togglecn(){
    var dd = $('.totalcn').css('display');
    if(dd == 'none'){
        $('.totalcn').show();
	}else{
        $('.totalcn').hide();
	}
}

$(".btnsrue").click(function(){
    jxsname = $(".jxsname-"+tid).val();
    $(".jxsname-"+tid).parent('span').text(jxsname);
    console.log();
    iaddress = $(".iaddress").val();
    $(".iaddress").parent('span').text(iaddress);
    if(tid == 8){
		$(".price").each(function(){
			vals = $(this).val();
			$(this).parent().text(vals);
		})
        var totalnew = 0.00;
		$("input[name='minitotal']").each(function(){
			vals = $(this).val();
            totalnew = parseFloat(totalnew)+parseFloat(vals);
            console.log(vals);
			$(this).parent().text(vals);
		})
		$(".big").text(digitUppercase(totalnew));
		$(".totalcn").attr('tdata',totalnew);
    }
});

function getTable(){
    var sheet  = XLSX.utils.table_to_sheet($("#template"+tid)[0]);
    if(!jxsname){
        jxsname = '{{$title}}';
	}
    openDownloadDialog(sheet2blob(sheet), jxsname+ '.xls');
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

    digitUppercase = function(n) {
        var fraction = ['角', '分'];
        var digit = [
            '零', '壹', '贰', '叁', '肆',
            '伍', '陆', '柒', '捌', '玖'
        ];
        var unit = [
            ['元', '万', '亿'],
            ['', '拾', '佰', '仟']
        ];
        var head = n < 0 ? '欠' : '';
        n = Math.abs(n);
        var s = '';
        for (var i = 0; i < fraction.length; i++) {
            s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');
        }
        s = s || '整';
        n = Math.floor(n);
        for (var i = 0; i < unit[0].length && n > 0; i++) {
            var p = '';
            for (var j = 0; j < unit[1].length && n > 0; j++) {
                p = digit[n % 10] + unit[1][j] + p;
                n = Math.floor(n / 10);
            }
            s = p.replace(/(零.)*零$/, '').replace(/^$/, '零') + unit[0][i] + s;
        }
        return head + s.replace(/(零.)*零元/, '元')
            .replace(/(零.)+/g, '零')
            .replace(/^整$/, '零元整');
    }
</script>