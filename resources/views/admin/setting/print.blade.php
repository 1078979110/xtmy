<div class="panel panel-default">
  	<div class="panel-heading">打印选项</div>
    <div class="panel-body">
        <form action="/admin/print/startprint" method="post" class="form-horizontal">
        	<div class="form-group">
        	<div class="form-group" id="jxs">
        		<label class="col-sm-1 control-label">模板</label>
        		<div class="col-sm-7">
        			<table class="table">
            			<thead>
            				<tr>
            					<th align="center">选择</th>
            					<th align="center">模板名称</th>
            					<th align="center">模板结果示图</th>
            				</tr>
            			</thead>
            			<tbody>
            				@foreach ( $templatesjxs as $jxs)
            				<tr>
            					<td><div class="radio"><input type="radio" name="template" required value="{{ $jxs['id'] }}"  style="margin-left: 10px"></div></td>
            					<td>{{$jxs['template']}}</td>
            					<td><img src="/storage/{{ $jxs['images'] }}" width="250" height="100"></td>
            				</tr>
            				@endforeach
            			</tbody>
            		</table>
            	</div>
        	</div>
			<div class="form-group">
				<div class="col-sm-offset-1">
					<input type="hidden" name="id" value="{{$id}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<button type="reset" class="btn btn-warning">重置</button>
					<button type="submit" class="btn btn-primary">提交</button>
				</div>
			</div>
        </form>
    </div>
</div>
<script type="text/javascript">
	$("select[name='type']").change(function(){
		type = $(this).find("option:selected").val();
		if(type == 1){
			$("#jxs").show();
			$("#ywy").hide();
		}else{
			$("#jxs").hide();
			$("#ywy").show();
		}
	});
</script>
