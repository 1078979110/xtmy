<?php

namespace App\Admin\Controllers;

use App\Site;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SiteController extends AdminController
{
    protected $title = '网站设置';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Site());
        $grid->column('sitename','网站名称');
        $grid->column('siteaddress','地址');
        $grid->column('telephone','电话');
        $grid->column('copyright','版权');
        $grid->column('banners','轮播图')->carousel();
        $grid->actions(function($actions){
            $actions->disableDelete();
            $actions->disableView();
        });
            $grid->disablePagination();
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableColumnSelector();
            $grid->disableCreateButton();
        return $grid;
    }
    protected function form(){
        $form  = new Form(new Site());
        $form->text('sitename','网站名称');
        $form->text('siteaddress','地址');
        $form->text('telephone','电话');
        $form->text('copyright','版权');
        $form->multipleImage('banners','轮播图')->sortable()->removable()->help('轮播图推荐尺寸为：1200*280像素');
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        return $form;
    }
    /* public function site(Content $content){
        $content->title('网站设置');
        $site = Site::find(1)->toArray();
        $form = new Form($site);
        $form->action('/admin/sites/siteinfo');
        $form->text('sitename','网站名称');
        $form->text('siteaddress','地址');
        $form->text('telephone','电话');
        $form->text('copyright','版权');
        $form->multipleImage('banners','轮播图')->sortable()->removable()->help('轮播图推荐尺寸为：1200*280像素');
        $form->hidden('id');
        $content->body($form);
        return $content;
    }
    
    protected function siteInfo(){
        if(request()->isMethod('post')){
            $data = $_POST;
            var_dump($data);
            exit;
            unset($data['_token']);
            $result = Site::where('id',$data['id'])->update($data);
            if($result){
                admin_toastr('操作成功','success');
                return redirect('/admin/sites');
            }else{
                admin_toastr('操作失败','error');
                return redirect('/admin/sites');
            }
        }
    } */
}
