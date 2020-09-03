<div class="panel panel-default">
  <div class="panel-heading">订单详情</div>
  <div class="panel-body">
    <form class="form-inline" action="/admin/api/changeprice" method="post">
    	<table class="table">
			<thead>
				<tr><th>id</th><th>药品名称</th><th>药品规格</th><th>数量</th><th>单位</th><th>价格</th></tr>
			</thead>
			<tbody>
			@foreach($info as $i=>$in)
				<tr>
				<td>{{$in['id']}}<input type="hidden" value="{{$in['id']}}" name="info[{{$i}}][id]"></td>
				<td>{{$in['medicinal']}}<input type="hidden" value="{{$in['medicinal']}}" name="info[{{$i}}][medicinal]"></td>
				<td>{{$in['medicinalnum']}}<input type="hidden" value="{{$in['medicinalnum']}}" name="info[{{$i}}][medicinalnum]"></td>
				<td>{{$in['num']}}<input type="hidden" value="{{$in['num']}}" name="info[{{$i}}][num]"></td>
				<td>{{$in['unit']}}<input type="hidden" value="{{$in['unit']}}" name="info[{{$i}}][unit]"></td>
				<td><input type="text" value="{{$in['price']}}" name="info[{{$i}}][price]" class="form-control"></td></tr>
			@endforeach
			</tbody>
			<tfoot><tr><td colspan="6">
				<input type="hidden" value="{{$id}}" name="id">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button class="btn btn-primary pull-right">确定</button>
				<button type="reset" class="btn btn-warning pull-right" style="margin-right: 5px">重置</button></td></tr></tfoot>
		</table>
    </form>
  </div>
</div>
