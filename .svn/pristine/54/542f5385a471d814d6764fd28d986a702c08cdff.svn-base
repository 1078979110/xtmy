<?php

namespace App\Admin\Controllers;

use App\Medicinal;
use App\Productline;
use App\Producer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Category;
use Encore\Admin\Admin;
use App\Admin\Extensions\Tools\SearchMedicinal;
use App\Admin\Extensions\Exporter\MyCsvExporter;
use App\Admin\Extensions\Tools\ExcelImport;
use App\Admin\Extensions\Tools\SetPrice;
use App\Admin\Extensions\Tools\ShowImg;
class MedicinalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '器械药品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Medicinal());
        $grid->filter(function($filter){
            $filter->like('medicinal','药品名称');
            $producer = Producer::pluck('name','id');
            $filter->equal('producer_id','厂家')->select($producer);
        });
        
        $grid->column('medicinal', '药品名称');
        $grid->column('medicinalnum', '产品编号');
        $grid->column('producer_id', '厂家')->display(function($producer_id){
            return Producer::getProducerNameById($producer_id);
        });
        $grid->column('line_id', '产品线')->display(function($line_id){
            return Productline::getLineNameById($line_id);
        });
        $grid->column('category_id','分类')->display(function($category_id){
            return Category::getCategoryNameById($category_id);
        });
        $grid->column('manufactur','生产厂商');
        $grid->column('specification','规格');
        $grid->column('stock','库存');
        $grid->column('batchnumber','批次');
        $grid->column('makedate','生产日期')->display(function($makedate){
            if(empty($makedate)){
                return $makedate;
            }else{
                return date('Y-m-d', strtotime($makedate));
            }
        });
            $grid->column('invalidate','失效日期')->display(function($invalidate){
                if(empty($invalidate)){
                    return $invalidate;
                }else{
                    return date('Y-m-d', strtotime($invalidate));
                }
        });
        $grid->column('registnum','注册证书');
        $grid->column('registivalidate','注册证失效日期')->display(function($registivalidate){
            if(empty($registivalidate)){
                return $registivalidate;
            }else{
                if(date('Y-m-d', strtotime($registivalidate)) == '1970-01-01'){
                    return $registivalidate;
                }else{
                    return date('Y-m-d', strtotime($registivalidate));
                }
            }
        });
        $grid->column('storagecondition','存储条件');
        $grid->column('status','状态')->display(function($status){
            $js = <<<SCRIPT
            $(".changeup").click(function(){
                var id = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    url:"/admin/api/medicinalstatus",
                    method:"post",
                    data:{'id':id,'status':status},
                    success:function(res){  
                            toastr.success(res.msg,res.title,setTimeout(function (){window.location.reload();}, 4000))
                    }
                });
            });
            $(".changedown").click(function(){
                var id = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    url:"/admin/api/medicinalstatus",
                    method:"post",
                    data:{'id':id,'status':status},
                    success:function(res){  
                            toastr.success(res.msg,res.title,setTimeout(function (){window.location.reload();}, 4000))
                    }
                });
            });
SCRIPT;
            Admin::script($js);
            $str = ($status==1)?'<button class="btn btn-warning btn-xs">已下架</button>':'<button class="btn btn-info btn-xs">已上架</button>';
            $str .= ($status==1)?'<a href="javascript:void(0)" data-id="'.$this->id.'" data-status="'.$this->status.'" class="changeup">上架</a>':'<a href="javascript:void(0)" data-id="'.$this->id.'" data-status="'.$this->status.'" class="changedown">下架</a>';
            return $str;
        });
        //$grid->disableFilter();
        $grid->exporter('MyCsvExporter');
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->tools(function($tools){
            //$tools->append(new SearchMedicinal());
            $tools->append(new ExcelImport());
            $tools->append(new SetPrice());
            //$tools->append(new ShowImg('excel_medicinal.png'));
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
        $show = new Show(Medicinal::findOrFail($id));
        $show->field('id','ID');
        $show->field('medicinal','器械名称');
        $show->field('medicinalnum', '产品编号');
        $show->field('manufacturinglicense', '许可证号');
        $show->field('manufactur', '生产厂商');
        $show->field('producer_id','厂家')->as(function($producer_id){
            return Producer::where('id', $producer_id)->value('name');
        });
        $show->field('line_id','产品线')->as(function($line_id){
            return Productline::where('id', $line_id)->value('linename');
        });
        $show->field('category_id','分类')->as(function($category_id){
            return Category::where('id', $category_id)->value('categoryname');
        });
        $show->field('specification','规格型号');
        $show->field('unit','单位');
        $show->field('stock','数量');
        $show->field('batchnumber','批号');
        $show->field('makedate','生产日期')->format('YYYY-MM-DD');
        $show->field('invalidate','失效日期')->format('YYYY-MM-DD');
        $show->field('registnum','注册证号');
        $show->field('registivalidate','注册证失效日期')->format('YYYY-MM-DD');
        $show->field('storagecondition','储存条件');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Medicinal());
        $form->text('medicinal','器械名称');
        $form->text('medicinalnum','产品编号');
        $form->text('manufacturinglicense','许可证号');
        $form->select('producer_id', '厂家')->options(function(){
            return Productline::getProducerIdName();
        })->load('line_id', '/admin/api/line');
        $form->select('line_id','产品线')->options(function($line_id){
            return Productline::where('id', $line_id)->pluck('linename','id');
        })->load('category_id','/admin/api/category');
        $form->select('category_id','分类')->options(function($category_id){
            return Category::where('id', $category_id)->pluck('categoryname', 'id');
        });
        $form->text('manufactur','生产厂商');
        $form->text('specification','规格型号');
        $form->text('unit','单位');
        $form->text('stock','数量');
        $form->text('batchnumber','批号');
        $form->date('makedate','生产日期')->format('YYYY-MM-DD');
        $form->date('invalidate','失效日期')->format('YYYY-MM-DD');
        $form->text('registnum','注册证号');
        $form->date('registivalidate','注册证失效日期')->format('YYYY-MM-DD');
        $form->text('storagecondition','储存条件');

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
