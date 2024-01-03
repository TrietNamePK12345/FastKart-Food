<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR; // Thuộc tính tĩnh | thuộc về class và 0 thuộc về 1 đối tượng cụ thể.
    // Cho phép truy cập mà không cần tạo đối tượng| Application::$ROOT_DIR
    public Router $router; // 1 đối tượng: Class Router
    public Request $request;// 1 đối tượng: Class Request
    public Response  $response;// 1 đối tượng: Class Response
    public static  Application $app;
    public function __construct($rootPath) // hàm khởi tạo
{
    self::$ROOT_DIR = $rootPath; // tham chiếu đến tt or pt tĩnh
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
}

    public function run() // Kích hoạt ứng dụng
    {
        echo $this->router->resolve();
    }

}