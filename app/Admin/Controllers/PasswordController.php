<?php

namespace App\Admin\Controllers;

use App\Password;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Admin;
//use Encore\Admin\Grid;
//use Encore\Admin\Show;
use App\Salelist;
class PasswordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '密码管理';
    
    public function setAdmin(Content $content){
        $content->title('批量设置密码');
        if(request()->isMethod('post')){
            $adminRole = request()->post('admin_role');
            $saleRole = request()->post('sale_role');
            $passWord = request()->post('password');
            $sales = Salelist::where('type',$saleRole)->get(['id','name','telephone'])->toArray();
            foreach ($sales as $key=>$val){
                $dataAdminRole = array(
                    'username' => $val['telephone'],
                    'password' => empty($passWord)?$val['password']:bcrypt($passWord),
                    'name' =>$val['name'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                );
                if(!empty($passWord))
                    Salelist::where('telephone',$val['telephone'])->update(['password'=>bcrypt($passWord),'updated_at'=>date('Y-m-d H:i:s', time())]);
                $user_id = DB::table('admin_users')->insertGetId($dataAdminRole);
                $dataAdminRoleUsers = array(
                    'role_id' => $adminRole,
                    'user_id' => $user_id
                );
                DB::table('admin_role_users')->insert($dataAdminRoleUsers);
            }
            admin_toastr('设置成功','success');
            return response()->redirectTo('/admin/salelists');
        }
        $form = new \Encore\Admin\Widgets\Form();
        $form->action('setadmin');
        $form->select('admin_role','选择管理分组')->options(function(){
            return DB::table('admin_roles')->where('id','!=',1)->pluck('name','id');
        });
        $form->select('sale_role','选择销售分组')->options(function(){
            return DB::table('usertypes')->pluck('usertype','id');
        });
        $form->password('password','默认密码')->rules('required');
        $content->body($form);
        $js = <<<SCRIPT
            
SCRIPT;
            Admin::script($js);
            return $content;
    }
    
    public function setPwdByUserId(Content $content, $id){
        $content->title('密码设置');
        $form = new \Encore\Admin\Widgets\Form();
        $form->action('/admin/password/setpwd');
        $phone = Salelist::where('id',$id)->value('telephone');
        $form->text('username','用户名')->default($phone)->readonly();
        $form->password('password','新密码')->rules('required');
        $content->body($form);
        $js = <<<SCRIPT
                
SCRIPT;
        Admin::script($js);
        return $content;
    }
    
    public function setPwd(){
        if(request()->isMethod('post')){
            $username = request()->post('username');
            $password = request()->post('password');
            $re = Salelist::where('telephone',$username)->update(['password'=>bcrypt($password),'updated_at'=>date('Y-m-d H:i:s', time())]);
            DB::table('admin_users')->where('username',$username)->update(['password'=>bcrypt($password)]);
            admin_toastr('设置成功','success');
            return response()->redirectTo('/admin/salelists');
        }
        
    }
    
}
