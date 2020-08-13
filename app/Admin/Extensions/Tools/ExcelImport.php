<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class ExcelImport extends  AbstractTool{
    protected $view = 'admin.tools.excelimport';
    protected function js(){
        return <<<EOT
        $(".excel").click(function(){
           var url = window.location.href;
            console.log(url);
            window.location.href='/admin/excel'
        });
EOT;
    }
        
    public function render(){
        Admin::script($this->js());
        return view($this->view);
    }
}