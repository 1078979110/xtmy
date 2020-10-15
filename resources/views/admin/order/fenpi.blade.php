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
                                <option data-id="{{$medicinal->id}}" value="{{$medicinal->medicinal_id}}" @if($item->orders_diaodu_id == $medicinal->id)selected="selected"@endif>
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
                        <input type="hidden" name="fenpi[{{$item->id}}][orders_diaodu_id]" value="{{$item->orders_diaodu_id}}">
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
            str += '<option data-id="'+ medicinals[i].id +'" value="' + medicinals[i].medicinal_id + '">[名称：' +  medicinals[i].medicinal + ']-[货号：' + medicinals[i].medicinalnum + ']-[数量：'+medicinals[i].num+']</option>\n';
        }
        str +=  '</select>\n'+
            '</div>\n'+
            '<div class="col-sm-1">\n'+
            '<input type="text" name="fenpi[' + key + '][num]" placeholder="请输入数量" class="form-control num">\n'+
            '</div>\n';
        str += '<div class="col-sm-1">\n' +
            '<button type="button" class="btn btn-danger btndel"><i class="fa fa-trash"></i></button>\n' +
            '<input type="hidden" name="fenpi['+key+'][orders_diaodu_id]" value="">'+
            '</div>';

        $("#foot").before(str);
        $(".btndel").click(function(){
            $(this).parent().parent().remove();
        });
        $(".product").change(function(){
            selected_ = $(this).find("option:selected").attr('data-id');
            $(this).parent().parent().children().find("input[type='hidden']").val(selected_);
        });
    });

    $(".product").change(function(){
        selected_ = $(this).find("option:selected").attr('data-id');
        $(this).parent().parent().children().find("input[type='hidden']").val(selected_);
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
        var medicinals_o = [];
        var tem_id = [];
        medicinals.forEach(function(item, index){
            offset = tem_id.indexOf(item.medicinal_id);
            console.log(offset);
            if( offset == -1){
                medicinals_o.push({
                    id:item.id,
                    order_id:item.order_id,
                    medicinal_id:item.medicinal_id,
                    num:item.num
                });
                tem_id.push(item.medicinal_id);
            }else{
                medicinals_o[offset].num = item.num + medicinals_o[offset].num;
            }
        });


        if(endList.length != medicinals_o.length){
            alert('有部分商品没有完成商品分批')
            return false
        }
        //console.log(endList)
        var flag = true
        console.log(medicinals_o);
        console.log(endList);
        medicinals_o.forEach((item,index)=>{
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