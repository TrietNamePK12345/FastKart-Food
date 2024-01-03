<?php

namespace app\core;

class Router
{
    public Request $request;
    public Response  $response;
    protected  array  $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    // Xử lý và trả về response tương ứng với route được xác định từ request
    public function resolve()
    {

        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView("404");
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
       return call_user_func($callback);
    }

    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // hàm str_replace : thay thế.
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent() //layout chung cho toàn bộ trang web
    {
        ob_start(); //bắt đầu bộ đệm đầu ra
        include_once Application::$ROOT_DIR."/Views/layouts/main.php";
        return ob_get_clean(); // lấy nội dung từ bộ đệm đầu ra sau khi include file layout.
    }

    protected  function renderOnlyView($view)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/Views/$view.php";
        return ob_get_clean();
    }
}