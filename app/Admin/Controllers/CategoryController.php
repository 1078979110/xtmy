<?php

namespace App\Admin\Controllers;

use App\Category;
use App\Productline;
use App\Producer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分类';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', 'ID');
        $grid->column('categoryname', '分类名称');
        $grid->column('line_id','产品线')->display(function($line_id){
            return Productline::getLineNameById($line_id);
        });
        $grid->column('producer_id', '生产商')->display(function($producer_id){
            return Producer::getProducerNameById($producer_id);
        });
        /* $grid->column('image','图片')->display(function($image){
            if(!empty($image))
            return '<image src="/storage/'.$image.'" width="150" height="90">';
            else 
                return '暂无图片';
        }); */
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableRowSelector();
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('categoryname','分类名称');
        $show->field('line_id', '产品线')->as(function($line_id){
            return Productline::getLineNameById($line_id);
        });
        $show->field('producer_id', '生产商')->as(function($producer_id){
            return Producer::getProducerNameById($producer_id);
        });
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
        $form = new Form(new Category());

        $form->text('categoryname', '分类名称')->rules('required|max:20');
        $form->select('producer_id', '生产商')->options(function(){
            return Productline::getProducerIdName();
        })->load('line_id', '/admin/api/line');
        $form->select('line_id', '生产线');
        /* $form->image('image','图片')->required(true); */
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
