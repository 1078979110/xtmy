<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
class ShowImg extends AbstractTool{
    public $imgsrc;
    public function __construct($img){
        $this->imgsrc = $img;
    }
    protected function script(){
        return  <<<SCRIPT
        $("#show").click(function(){
            imgsrc = $(this).attr('data-img');
            window.open('/admin/images/'+imgsrc,'_blank');
        });
SCRIPT;
    }
    public function render(){
        Admin::script($this->script());
        
        return view('admin.tools.showimg',['img'=>$this->imgsrc]);
    }
}