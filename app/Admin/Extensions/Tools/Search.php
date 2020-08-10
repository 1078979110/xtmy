<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Concerns\HasQuickSearch;
use Illuminate\Support\Arr;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class Search extends AbstractTool{
    protected $placeholder = "请输入手机号或者名称";
    protected function script(){
        return  <<<SCRIPT
        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
        }
         typev = getUrlParam('type');
         keyv = getUrlParam('key');
         $("select[name='type']").find('option[value="typev"]').prop("selected", true);
        $("input[name='key']").val(keyv); 
SCRIPT;
    }

    public function render(){
        $query = request()->query();
        Arr::forget($query, HasQuickSearch::$searchKey);
        $vars = [
            'action' => request()->url().'?'.http_build_query($query),
            'type'         => isset($query['type'])?$query['type']:0,
            'key'       => isset($query['key'])?$query['key']:'', 
            'placeholder' => $this->placeholder
        ]; 
        return view('admin.tools.search',$vars);
    }
}