<?php

namespace App\Admin\Controllers;

use App\Hospital;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Salelist;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;
class HospitalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '医院管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Hospital());
        $user_id = Admin::user()->id;
        $username = DB::table('admin_users')->where('id',$user_id)->value('username');
        $buyerid = Salelist::where('telephone',$username)->value('id');
        $user_roles = Admin::user()->roles->toArray();
        if($user_roles[0]['id'] ==4){
                $grid->model()->where('belongto',$buyerid);
        }
        
        $grid->column('id','ID');
        $grid->column('hospital', '医院名称');
        $grid->column('contactman', '联系人');
        $grid->column('telephone', '联系人电话');
        $grid->column('address', '医院地址');
        $grid->column('belongto', '所属业务员')->display(function($belongto){
            return Salelist::where('id',$belongto)->value('name');
        });
        $grid->column('department', '医院部门');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '修改时间');
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
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
        $show = new Show(Hospital::findOrFail($id));

        $show->field('id', __('编号'));
        $show->field('hospital', __('医院名称'));
        $show->field('contactman', __('联系人'));
        $show->field('telephone', __('联系电话'));
        $show->field('address', __('医院地址'));
        $show->field('belongto', __('绑定业务员'))->as(function($belongto){
            return Salelist::where('id', $belongto)->value('name');
        });
        $show->field('department', __('部门'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Hospital());

        $form->text('hospital', __('医院名称'));
        $form->text('contactman', __('联系人'));
        $form->mobile('telephone', '联系电话');
        $form->text('address', __('医院地址'));
        $form->select('belongto', __('绑定业务员'))->options(function(){
            return Salelist::where('type','2')->orderBy('id', 'asc')->pluck('name','id');
        });
        $form->textarea('department', __('部门'));
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
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
