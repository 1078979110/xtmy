<?php

namespace App\Admin\Controllers;

use App\Site;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Form;

class SiteController extends AdminController
{
    public function site(Content $content){
        $content->title('网站设置');
        $site = Site::find(1)->toArray();
        $form = new Form($site);
        $form->action('/admin/sites/siteinfo');
        $form->text('sitename','网站名称');
        $form->text('siteaddress','地址');
        $form->text('telephone','电话');
        $form->hidden('id');
        $content->body($form);
        return $content;
    }
    
    protected function siteInfo(){
        if(request()->isMethod('post')){
            $data = $_POST;
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
    }
}
