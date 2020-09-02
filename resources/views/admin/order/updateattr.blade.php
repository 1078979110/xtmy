<div class="panel panel-default">
    <div class="panel-heading">补充信息</div>
    <div class="panel-body">
        <form action="/admin/api/updateattr" method="post">
            <div class="form-group">
                <div class="form-inline pull-right"><input type="hidden" value="{{ csrf_token() }}" name="_token" ><input type="hidden" value="{{$id}}" name="id"><button type="submit" class="btn btn-primary com">确定</button></div>
            </div>
            <div class="form-group">
                <table class="table">
                    <thead><tr><th>名称</th><th>规格</th><th>数量</th><th>价格</th><th>批号</th><th>灭菌批号</th><th>生产日期</th><th>失效日期</th><th>装箱规格</th></tr></thead>
                    <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <td>{{$product['medicinal']}}</td>
                            <td>{{$product['medicinalnum']}}</td>
                            <td>{{$product['num']}}
                            <td>{{$product['price']}}</td>
                            <td><input type="text" name="info[{{$product['id']}}][batchnumber]" value="{{$product['batchnumber']}}" class="form-control"></td>
                            <td><input type="text" name="info[{{$product['id']}}][novirus]" value="{{$product['novirus']}}" class="form-control"></td>
                            <td><input type="text" name="info[{{$product['id']}}][makedate]" value="{{$product['makedate']}}" class="form-control"></td>
                            <td><input type="text" name="info[{{$product['id']}}][invalidate]" value="{{$product['invalidate']}}" class="form-control"></td>
                            <td><input type="text" name="info[{{$product['id']}}][boxformat]" value="{{$product['boxformat']}}" class="form-control"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>