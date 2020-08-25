<div class="panel panel-default">
  <div class="panel-heading">赠品设置</div>
  <div class="panel-body">
  	<div class="form-group">
  		<div class="form-inline"><input name="q" value="" placeholder="请输入药品名或者规格" class="form-control" style="width: 200px"><button id="search" class="btn btn-primary"><i class="fa fa-search"></i></button></div>
  		<div class="form-inline"><input type="hidden" value="{{ csrf_token() }}" name="_token" ><input type="hidden" value="{{$id}}" name="id"><button class="btn btn-primary com">确定</button></div>
	</div>
  	<form action="/admin/api/gift" method="post">
  	<div class="form-group">
		<table class="table">
			<thead><tr><th>名称</th><th>规格</th><th>库存</th><th>赠送数量</th></tr></thead>
			<tbody id="result"></tbody>
		</table>
	</div>
	</form>
  </div>
</div>
<script>
	$("#search").click(function(){
		var q = $("input[name='q']").val();
		var tablestr = '';
		$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		$.ajax({
			url:'/admin/api/searchm', 
			data:{'q':q},
			method:'post',
			success:function(res){
				if(res.length<1){
					return toastr.warning('搜索结果为空！')}
					for(var i in res){
						tablestr += '<tr><td>'+res[i]['medicinal']+'<input type="hidden" name="gift['+i+'][medicinal]" value="'+res[i]['medicinal']+'"></td><td>'+res[i]['specification']+'<input type="hidden" name="gift['+i+'][specification]" value="'+res[i]['specification']+'"></td><td>'+res[i]['stock']+'<input type="hidden" name="gift['+i+'][stock]" value="'+res[i]['stock']+'"></td><td><input type="number" value="" name="gift['+i+'][num]" min="0" max="'+res[i]['stock']+'" class="form-control"><input type="hidden" name="gift['+i+'][id]" value="'+res[i]['id']+'"></td></tr>';
					}
					$("#result").html(tablestr);
				}
		});
	});
</script>