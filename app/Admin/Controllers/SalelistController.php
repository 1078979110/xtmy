<?php

namespace App\Admin\Controllers;

use App\Hospitalprice;
use App\Salelist;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Usertype;
use App\Hospital;
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
            $filter->disableIdFilter();
            $filter->like('name', '姓名');
            $arr = Usertype::pluck('usertype','id');
            $filter->equal('type','类型')->select($arr);
        });
        $grid->column('name','名称');
        $grid->column('telephone','手机号');
        $grid->column('type','销售类型')->display(function($type){
            return Salelist::getTypeNameByTypeId($type);
        });
        $grid->column('名下医院')->display(function(){
            return '查看';
        })->expand(function(){
            $list = Hospital::where('belongto', $this->id)->get(['hospital', 'contactman','telephone','address'])->toArray(true);
            return new Table(['医院', '联系人', '联系电话', '地址'], $list);
        });
        $state = [
            'on'=>['value'=>0,'text'=>'正常', 'color'=>'success'],
            'off'=>['value'=>1,'text'=>'冻结', 'color'=>'danger']
        ];
        $grid->column('status', '状态')->switch($state);
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '修改时间');
        $grid->disableExport();
        //$grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->actions(function($action){
            $action->disableview();
        });
        $grid->tools(function ($tools) {
            //$tools->append(new SetAdmin());
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
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });
        $state = [
            'on'=>['value'=>0,'text'=>'正常', 'color'=>'success'],
            'off'=>['value'=>1,'text'=>'冻结', 'color'=>'danger']
        ];
        $form->switch('status','状态')->states($state);
        $form->text('depart','部门')->help('经销商填写,业务员忽略');
        $form->text('address','地址')->help('经销商填写,业务员忽略');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        return $form;
    }
}
