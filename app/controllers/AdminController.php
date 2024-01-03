<?php
class AdminController extends Controller{
    public $admin, $data=[];

    public function __construct(){
       
    }

    public function dashboard(){
        $this->data['sub_content']['title'] ='Dashboard';
        $this->data['content'] = 'admin/dashboard/index';
        $this->data['page_title'] = 'Dashboard';
        $this->render('layouts/admin_layout', $this->data);
    }
    
    public function login(){
        $this->data['sub_content']['title'] ='Đăng Nhập';
        $this->data['content'] = 'client/login/login';
        $this->render('layouts/client_layout', $this->data);
    }

    public function register(){
        $this->data['sub_content']['title'] ='Đăng Kí';
        $this->data['content'] = 'client/login/register';
        $this->render('layouts/client_layout', $this->data);
    }

    public function forgot(){
        $this->data['sub_content']['title'] ='Quên Mật Khẩu';
        $this->data['content'] = 'client/login/forgot';
        $this->render('layouts/client_layout', $this->data);
    }


}

?>