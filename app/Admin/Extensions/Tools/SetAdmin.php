<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class SetAdmin extends AbstractTool{
        
    protected function script(){
        return  <<<SCRIPT
        $("#setadmin").click(function(){
                    window.location.href = '/admin/password/setadmin'
                });
SCRIPT;
    }
    public function render(){
        Admin::script($this->script());
        return view('admin.tools.setadmin');
    }
}