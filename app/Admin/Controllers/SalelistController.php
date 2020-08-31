<?php

namespace App\Admin\Controllers;

use App\Salelist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\SetAdmin;
use App\Admin\Extensions\Tools\Search;
use Encore\Admin\Grid\Model;
use Illuminate\Support\Facades\DB;
use App\Usertype;
class SalelistController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '销售管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Salelist());

        $grid->filter(function($filter){
            $filter->like('name', '姓名');
            $arr = Usertype::pluck('usertype','id');
            $filter->equal('type','类型')->select($arr);
        });
        $grid->column('name','名称');
        $grid->column('telephone','手机号');
        $grid->column('type','销售类型')->display(function($type){
            return Salelist::getTypeNameByTypeId($type);
        });
        $grid->column('status', '状态')->display(function($status){
            return $status?'<button class="btn btn-warning">冻结</button>':'<button class="btn btn-info">正常</button>';
        });
        $grid->column('password', '重置密码')->display(function(){
            return '<a class="btn btn-warning" href="/admin/password/setpwd/'.$this->id.'">去重置</a>';
        });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '修改时间');
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->actions(function($action){
            $action->disableview();
        });
        $grid->tools(function ($tools) {
            $tools->append(new SetAdmin());
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Salelist::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Salelist());
        $form->text('name','名称');
        $form->mobile('telephone','电话');
        $form->select('type','销售类型')->options(function(){
           return Salelist::getTypeIdName(); 
        });
        $form->password('password','密码')->required();
        $form->saving(function(Form $form){
            $form->password = bcrypt($form->password);
        });
        $form->select('status','状态')->options(['0'=>'正常','1'=>'冻结']);
        $form->text('depart','部门')->help('经销商填写,业务员忽略');
        $form->text('address','地址')->help('经销商填写,业务员忽略');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            //$footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        return $form;
    }
}
