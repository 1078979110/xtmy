<div class="panel panel-default">
  <div class="panel-heading">订单详情</div>
  <div class="panel-body">
    <form class="form-horizontal" action="/admin/api/changeinfo" method="post">
    	<div class="form-group">
    		<label class="col-sm-1 control-label">@if(isset($info['telephone']))电话 @else用户名@endif</label>
    		<div class="col-sm-5">
    		<input type="text" name="telephone" value="{{ isset($info['telephone'])?$info['telephone']:$info['username'] }}" readonly="readonly" class="form-control">
    		</div>
    	</div>
    	<div class="form-group">
    		<label class="col-sm-1 control-label">名称</label>
    		<div class="col-sm-5">
    		<input type="text" name="name" value="{{ $info['name'] }}" class="form-control">
    		</div>
    	</div>
    	<div class="form-group">
    		<label class="col-sm-1 control-label">新密码</label>
    		<div class="col-sm-5">
    		<input type="password" name="password" value="" class="form-control" placeholder="请输入新密码，否则请忽略">
    		</div>
    	</div>
    	@if(isset($info['type']) && ( $info['type']==1))
        	<div class="form-group">
        		<label class="col-sm-1 control-label">地址</label>
        		<div class="col-sm-5">
        		<input type="text" name="address" value="{{ $info['address'] }}" class="form-control">
        		</div>
        	</div>
    	@endif
    	<div class="form-group">
    		<div class="col-sm-offset-1 col-sm-5">
    			<input type="hidden" name="_token" value="{{csrf_token()}}">
    			<button type="reset" class="btn btn-warning">重置</button>
    			<button type="subbmit" class="btn btn-primary">确定</button>
    		</div>
    	</div>
    </form>
  </div>
</div>