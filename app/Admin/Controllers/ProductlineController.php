<?php

namespace App\Admin\Controllers;

use App\Productline;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
class ProductlineController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '产品线';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Productline());

        $grid->column('id', 'ID');
        $grid->column('linename', '产品线名称');
        $grid->column('producer_id','生产商')->display(function($producer_id){
            return Productline::getProducerNameById($producer_id);
        });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '修改时间');
        $grid->disableFilter();
        $grid->disableExport();
        //$grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->actions(function($actions){
            $actions->disableView();
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
        $show = new Show(Productline::findOrFail($id));
        $show->field('id', 'ID');
        $show->field('linename', '产品线名称');
        $show->field('producer_id','生产商')->display(function($producer_id){
            return Productline::getProducerNameById($producer_id);
        });
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '修改时间');

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Productline());

        $form->text('linename','产品线')->rules('required|max:20');
        $form->select('producer_id','生产商')->options(function(){
            return Productline::getProducerIdName();
        });
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
