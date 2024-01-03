<?php
class ShopController extends Controller
{

    public $data = [];

    public function __construct()
    {
    }

    public function index()
    {
        $this->data['sub_content']['page_title'] = 'Cửa Hàng';
        $this->data['content'] = 'client/shop/shop';
        $this->render('layouts/client_layout', $this->data);
    }

    public function detail()
    {
        $this->data['sub_content']['page_title'] = 'Chi Tiết Sản Phẩm';
        $this->data['content'] = 'client/shop/product_detail';
        $this->render('layouts/client_layout', $this->data);
    }


}
