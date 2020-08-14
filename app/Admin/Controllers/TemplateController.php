<?php

namespace App\Admin\Controllers;

use App\Prints;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TemplateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Prints';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Prints());

        $grid->column('id', __('ID'));
        $grid->column('template', __('模板名称'));
        $grid->column('templateslug', __('标识'));
        $grid->column('type', __('类型'))->display(function($type){
            return $type ==1?'经销商':'医院';
        });
        $grid->column('created_at', __('添加时间'));
        $grid->column('updated_at', __('删除时间'));

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
        $show = new Show(Prints::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('template', __('Template'));
        $show->field('templateslug', __('Templateslug'));
        $show->field('type', __('Type'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Prints());

        $form->text('template', __('模板名称'));
        $form->text('templateslug', __('标识'));
        $form->select('type', __('类型'))->options(function(){
            return ['1'=>'经销商','2'=>'医院'];
        });
        $form->image('images','结果示例图');

        return $form;
    }
}
