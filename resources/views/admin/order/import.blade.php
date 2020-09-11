<div class="panel panel-default">
    <div class="panel-heading">订单导入</div>
    <div class="panel-body">
        <form class="form-horizontal" action="/admin/api/importorder" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-sm-1">文件</label>
                <div class="col-sm-5">
                    <input type="file" name="orders" class="form-control" multiple>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-1"><input type="hidden" value="{{ csrf_token() }}" name="_token" ><button type="submit" class="btn btn-primary">确定</button></div>
            </div>
        </form>
    </div>
</div>