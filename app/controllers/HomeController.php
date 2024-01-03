<?php 
class HomeController extends Controller{
    public $data = [];

    public function __construct(){

    }

    public function index(){
        $this->data['sub_content']['page_title'] = 'Trang chủ';
        $this->data['content'] = 'client/home/index';
        $this->render('layouts/client_layout', $this->data);
    }
}


?>