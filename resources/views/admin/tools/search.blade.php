<form action="{!! $action !!}" pjax-container class="form-inline" style="display: inline-block;">
    <div class="form-group">
    	<select class="form-control" name="type">
    		<option value="0">==请选择销售类型==</option>
    		<option value="1" @if($type == 1) selected="selected" @endif>经销商</option>
    		<option value="2" @if($type == 2) selected="selected" @endif>业务员</option>
    	</select>
    </div>
    <div class="form-group">
        <input type="text" name="key" class="form-control grid-quick-search" style="width: 200px;" value="{{$key}}" placeholder="{{ $placeholder }}">
    </div>
    <div class="form-group" style="display: inline-block;">
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</form>
