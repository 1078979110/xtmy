<div class="panel panel-default">
    <div class="panel-heading">调度操作</div>
    <div class="panel-body">
        <form class="form-horizontal" action="/admin/api/splitorder" method="post" onsubmit="checknum()">
        {{--<form class="form-horizontal">--}}
            <div class="form-group">
                <div class="col-sm-1">
                    <label class="control-label" style="text-align: right">订单商品</label>
                </div>
                <div class="col-sm-7">
                    <label class="control-label">器械药品</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">数量</label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">仓库</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">操作</label>
                </div>
            </div>
            @foreach($diaodu as $key => $item)
                <div class="form-group diaodu liView" >
                    <label class="control-label col-sm-1"></label>
                    <div class="col-sm-7 ">
                        <select name="diaodu[{{$item->id}}][medicinal_id]" class="form-control product">
                            <option value="0">==请选择==</option>
                            @foreach($medicinals as $k=>$medicinal)
                                <option value="{{$medicinal->medicinal_id}}" @if($item->medicinal_id == $medicinal->medicinal_id)selected="selected"@endif>
                                    [名称：{{$medicinal->medicinal}}]-[货号：{{$medicinal->medicinalnum}}]-[数量：{{$medicinal->num}}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="diaodu[{{$item->id}}][num]" value="{{$item->num}}" placeholder="请输入数量" class="form-control num">
                    </div>
                    <div class="col-sm-2">
                        <select name="diaodu[{{$item->id}}][warehouse_id]" class="form-control warehouse">
                            <option value="0">==请选择==</option>
                            @foreach($warehouses as $k=>$warehouse)
                                <option value="{{$warehouse->id}}" @if($item->warehouse_id == $warehouse->id)selected="selected"@endif>{{$warehouse->username}}-{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger btndel"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            @endforeach

            <hr class="divider" id="line"></hr>
            @if(!empty($gifts))
            <div class="form-group">
                <div class="col-sm-1">
                    <label class="control-label" style="text-align: right">赠品</label>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">器械药品</label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label">赠品来源</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">数量</label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">仓库</label>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">说明</label>
                </div>
            </div>
            @foreach($gifts as $key=>$gift)
            <div class="form-group giftLi" id="gift">
                <label class="control-label col-sm-1"></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" readonly="readonly" value="{{$gift->medicinal}}-{{$gift->medicinalnum}}">
                    <input type="hidden" name="gift[{{$gift->id}}][id]" value="{{$gift->id}}">
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" readonly="readonly" value="{{$gift->origin}}">
                </div>
                <div class="col-sm-1">
                    <input type="text" class="form-control" value="{{$gift->num}}" readonly="readonly">
                </div>
                <div class="col-sm-2">
                    <select name="gift[{{$gift->id}}][warehouse_id]" class="form-control">
                        <option value="0">==请选择==</option>
                        @foreach($warehouses as $k=>$warehouse)
                            <option value="{{$warehouse->id}}" @if($gift->warehouse_id == $warehouse->id)selected="selected"@endif>{{$warehouse->username}}-{{$warehouse->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">该赠品只能选择被分配了赠品来源商品的仓库</div>
            </div>
            @endforeach
            @endif
            <div class="form-group" id="foot">
                <div class="col-sm-5 col-sm-offset-1">
                    <input type="hidden" value="{{$id}}" name="id">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" >
                    <button type="button" class="btn btn-info btnadd"><i class="fa fa-plus"></i> 新增</button>
                    <button type="submit" class="btn btn-primary submit">确定</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var medicinals = '';
    var warehouses = '';
    var gifts = {!! $gifts_josn !!}
    var key = '';
    //删除
    $(".btndel").click(function(){
        $(this).parent().parent().remove();
    });
    //新增
    $('.btnadd').click(function(){
        medicinals ={!! $medicinals_json !!}
            warehouses ={!! $warehouses_json !!}
            str = '';
            key = random_str();
            str =  '<div class="form-group liView" >\n'+
                    '<label class="control-label col-sm-1"></label>\n'+
                    '<div class="col-sm-7">\n'+
                    '<select name="diaodu[' + key + '][medicinal_id]" class="form-control product">\n' +
                    '<option value="0">==请选择==</option>\n';
        for(var i in medicinals){
            str += '<option value="' + medicinals[i].medicinal_id + '">[名称：' +  medicinals[i].medicinal + ']-[货号：' + medicinals[i].medicinalnum + ']-[数量：'+medicinals[i].num+']</option>\n';
        }
        str +=  '</select>\n'+
                '</div>\n'+
                '<div class="col-sm-1">\n'+
                '<input type="text" name="diaodu[' + key + '][num]" placeholder="请输入数量" class="form-control num">\n'+
                '</div>\n'+
                '<div class="col-sm-2">\n' +
                '<select name="diaodu[' + key + '][warehouse_id]" class="form-control warehouse">\n' +
                '<option value="0">==请选择==</option>';
        for(var j in warehouses){
            str += '<option value="' + warehouses[j].id +'">' +  warehouses[j].username+ '-' + warehouses[j].name + '</option>\n';
        }
        str +=  '</select>\n'+
                '</div>\n'+
                '<div class="col-sm-1">\n' +
                '<button type="button" class="btn btn-danger btndel"><i class="fa fa-trash"></i></button>\n' +
                '</div>';
        $("#line").before(str);
        $(".btndel").click(function(){
            $(this).parent().parent().remove();
        });
    });
//
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
            alert('请完成商品分库')
            return false
        }

        $('.liView').each(function (index,item) {
            allList.push({
                id:$(this).find('.product').val(),
                num: $(this).find('.num').val(),
                warehouse_id:$(this).find('.warehouse').val()
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
                    if(item_.warehouse_ids.indexOf(item.warehouse_id) == -1){
                        item_.warehouse_ids.push(item.warehouse_id)
                    }
                }
            })

        })

        if(endList.length != medicinals.length){
            alert('请完成商品分库')
            return false
        }
        //console.log(endList)
        console.log(gifts)
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
            alert('药品不全，请核对数量')
            return false
        }

        endList.forEach((item,index)=>{
            if(item.warehouse_ids[0] == '0'){
                flag = false
            }
        })

        console.log(endList)
        console.log('1111111')
        if(!flag){
            alert('请选择对应的仓库管理员')
            return false
        }

        var giftList = []
        gifts.forEach((item,index)=>{
            giftList.push({
                id: item.origin_id,
                warehouse_id: $('.giftLi').eq(index).find('select').val()
            })
        })



        giftList.forEach((item,index)=>{
            endList.forEach((item_,index_)=>{
                if(item.id == item_.id){
                    if(item_.warehouse_ids.indexOf(item.warehouse_id) == -1){
                        flag = false
                    }
                }
            })
        })

        if(!flag){
            alert('请选择与源商品对应的赠品仓库管理员')
            return false
        }

        console.log('提交')


    })
</script>