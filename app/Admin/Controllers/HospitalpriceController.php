<?php

namespace App\Admin\Controllers;

use App\Hospitalprice;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Hospital;
use App\Medicinal;
use App\Admin\Extensions\Tools\SetPrice;
use App\Admin\Extensions\Tools\ShowImg;
class HospitalpriceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '价格管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Hospitalprice());

        $grid->filter(function($filter){
            $hospital = Hospital::pluck('hospital','id');
            $filter->like('medicinalnum','产品货号');
            $filter->equal('hospitalid','医院')->select($hospital);
        });
        
        $grid->column('hospitalid', __('医院'))->display(function($hospitalid){
            return Hospital::where('id',$hospitalid)->value('hospital');
        });
        $grid->column('medicinalid', __('药品'))->display(function($medicinalid){
            return Medicinal::where('id',$medicinalid)->value('medicinal');
        });
        $grid->column('medicinalnum',__('产品货号'));
        //$grid->disableFilter();
        //$grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->column('price', __('价格'));
        $grid->actions(function($action){
            $action->disableView();
        });
            $grid->exporter('MyCsvExporter');
        $grid->tools(function($tools){
            $tools->append(new SetPrice());
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
        $show = new Show(Hospitalprice::findOrFail($id));

        $show->field('id', __('编号'));
        $show->field('hospitalid', __('医院'))->as(function($hospitalid){
            return Hospital::where('id', $hospitalid)->value('hospital');
        });
            $show->field('medicinalid', __('药品名称'))->as(function($medicinalid){
                return Medicinal::where('id', $medicinalid)->value('medicinal');
            });
        
        $show->field('price', __('价格'));
        $show->field('created_at', __('添加时间'));
        $show->field('updated_at', __('修改时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Hospitalprice());
        $form->select('hospitalid', __('医院'))->options(function(){
            return Hospital::pluck('hospital','id');
        });
         $form->select('medicinalid', __('药品'))->options(function($medicinalid){
             return Medicinal::where('id',$medicinalid)->pluck('medicinal','id');
         })->ajax('/admin/api/getmedicinals'); 
        $form->decimal('price', __('价格'));
        $form->saved(function(Form $form){
            $form->medicinalnum = Medicinal::where('id',$form->medicinalid)->value('medicinalnum');
            Hospitalprice::where('id',$form->model()->id)->update(['medicinalnum'=>$form->medicinalnum]);
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        return $form;
    }
}
