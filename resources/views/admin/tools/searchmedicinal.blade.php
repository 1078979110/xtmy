<form action="{!! $action !!}" pjax-container class="form-inline" style="display: inline-block;">
    <div class="form-group">
    	<select class="form-control" name="producer">
    		<option value="0">==请选择厂家类型==</option>
    		@foreach($prolist as $pro)
    		<option value="{{ $pro['id'] }}" @if($producer == $pro["id"]) selected="selected" @endif>{{ $pro['name'] }}</option>
    		@endforeach
    	</select>
    </div>
    <div class="form-group">
        <input type="text" name="key" class="form-control grid-quick-search" style="width: 200px;" value="{{ $key }}" placeholder="{{ $placeholder }}">
    </div>
    <div class="form-group" style="display: inline-block;">
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</form>

