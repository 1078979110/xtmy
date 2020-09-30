<div class="panel panel-default">
    <div class="panel-heading">调度操作</div>
    <div class="panel-body">
        <form class="form-horizontal" action="/admin/api/fenpiorder" method="post">
            <div class="form-group">
                <label class="control-label col-sm-1"></label>
                <div class="col-sm-7">
                    <label class="control-label">器械药品</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">数量</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">操作</label>
                </div>
            </div>
            @foreach($fenpi as $key => $item)
                <div class="form-group diaodu liView" >
                    <label class="control-label col-sm-1"></label>
                    <div class="col-sm-7 ">
                        <select name="fenpi[{{$item->id}}][medicinal_id]" class="form-control product">
                            <option value="0">==请选择==</option>
                            @foreach($medicinals as $k=>$medicinal)
                                <option value="{{$medicinal->medicinal_id}}" @if($item->medicinal_id == $medicinal->medicinal_id)selected="selected"@endif>
                                    [名称：{{$medicinal->medicinal}}]-[货号：{{$medicinal->medicinalnum}}]-[数量：{{$medicinal->num}}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="fenpi[{{$item->id}}][num]" value="{{$item->num}}" placeholder="请输入数量" class="form-control num">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger btndel"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            @endforeach
            <div class="form-group" id="foot">
                <div class="col-sm-5 col-sm-offset-1">
                    <input type="hidden" value="{{$id}}" name="id">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" >
                    <button type="button" class="btn btn-info btnadd"><i class="fa fa-plus"></i> 新增</button>
                    <button type="submit" class="btn btn-primary submit">确定</button></div>
            </div>
        </form>
    </div>
</div>
<script>

    var medicinals = {!! $medicinals_json !!};
    var key = '';

    //删除
    $(".btndel").click(function(){
        $(this).parent().parent().remove();
    });
    //新增
    $('.btnadd').click(function(){
        medicinals ={!! $medicinals_json !!},
            str = '';
        key = random_str();
        str =  '<div class="form-group liView" >\n'+
            '<label class="control-label col-sm-1"></label>\n'+
            '<div class="col-sm-7 ">\n'+
            '<select name="fenpi[' + key + '][medicinal_id]" class="form-control product">\n' +
            '<option value="0">==请选择==</option>\n';
        for(var i in medicinals){
            str += '<option value="' + medicinals[i].medicinal_id + '">[名称：' +  medicinals[i].medicinal + ']-[货号：' + medicinals[i].medicinalnum + ']-[数量：'+medicinals[i].num+']</option>\n';
        }
        str +=  '</select>\n'+
            '</div>\n'+
            '<div class="col-sm-1">\n'+
            '<input type="text" name="fenpi[' + key + '][num]" placeholder="请输入数量" class="form-control num">\n'+
            '</div>\n';
        str += '<div class="col-sm-1">\n' +
            '<button type="button" class="btn btn-danger btndel"><i class="fa fa-trash"></i></button>\n' +
            '</div>';

        $("#foot").before(str);
        $(".btndel").click(function(){
            $(this).parent().parent().remove();
        });
    });

    function random_str(){
        var strlength = 6;
        str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        randstr = '';
        for(i=0; i<strlength; i++){
            j = Math.floor(Math.random()*(35-0+1)+0);
            randstr += str[j];
        }
        return randstr;
    }

    //    提交
    $('.submit').click(function () {
//
        var endList = [];
        var allList = [];
        var checkList = [];

        console.log()
        console.log($('.liView').length)



        if(medicinals==''){
            alert('请完成商品分批！')
            return false
        }

        $('.liView').each(function (index,item) {
            allList.push({
                id:$(this).find('.product').val(),
                num: $(this).find('.num').val()
            })
        })

        $('.liView').each(function (index,item) {
            if(checkList.indexOf($(this).find('.product').val()) == -1){
                checkList.push($(this).find('.product').val())
            }
        })

        checkList.forEach((item,index)=>{
            endList.push({
                id:item,
                num: 0,
                warehouse_ids:[]
            })
        })

        allList.forEach((item,index)=>{
            endList.forEach((item_,index_)=>{
                if(item_.id == item.id){
                    item_.num += item.num*1
                }
            })
        })
        console.log(checkList);
        console.log(endList);
        console.log(medicinals);
        if(endList.length != medicinals.length){
            alert('有部分商品没有完成商品分批')
            return false
        }
        //console.log(endList)
        var flag = true
        medicinals.forEach((item,index)=>{
            endList.forEach((item_,index_)=>{
                if(item.medicinal_id == item_.id){
                    if(item_.num != item.num){
                        flag = false
                    }
                }
            })
        })
        console.log(flag)
        if(!flag){
            alert('药品数量分配不全，请核对数量')
            return false
        }

        console.log('提交')


    })

</script>