<?php
/**
 * Created by PhpStorm.
 * User: MNRC
 * Date: 2020/9/11
 * Time: 9:41
 */

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class OrderImport extends AbstractTool{
    protected function script(){
        return <<<SCRIPT
            $(".orderimport").click(function(){
                window.location.href = '/admin/excel/order'
            });
SCRIPT;

    }

    public function render(){
        Admin::script($this->script());
        return view('admin.extension.order');
    }
}