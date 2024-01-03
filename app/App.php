<?php
class App
{

    private  $__controller, $__action, $__params, $__routes, $__db;
    static public $app;
    function __construct()
    {
        global $routes, $config;

        self::$app = $this;

        $this->__routes = new Route();

        if (!empty($routes['default_controller'])) {
            $this->__controller = $routes['default_controller'];
        }

        $this->__action = 'index';
        $this->__params = [];
        
        if (class_exists('DB')) { // Global Query Builder
            $dbOject = new DB();
            $this->__db = $dbOject->db; // tác dụng là có thể viết câu truy vấn mà không cần phải qua model.
        }

        $this->handleUrl();
    }


    function getUrl()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            $url = $_SERVER['PATH_INFO'];
        } else {
            $url = '/';
        }

        return $url;
    }

    // Kĩ thuật liên quan đến xử lí mảng.
    public function handleUrl()
    {

        $url = $this->getUrl();
        $url = $this->__routes->handleRoute($url);
        // Load MiddleWare
        $this->handleGlobalMiddleWare($this->__db);
        $this->handleRouteMiddleWare($this->__routes->getUri(), $this->__db); 
        
        // Load App Service User
        $this->handleAppSeviceUser($this->__db);
        
        // tách chuỗi thành mảng : explode || array_filter : chia chuỗi thành mảng các chuỗi con và loại bỏ các phần tử rỗng
        $urlArr = array_filter(explode('/', $url));
        // array_values(): tạo ra 1 mảng mới chỉ chứa các giá trị từ mảng ban đầu,
        // và các giá trị này sẽ có các chỉ số là các số nguyên liên tục, bắt đầu từ 0.
        $urlArr = array_values($urlArr);
        $urlCheck = '';

        if (!empty($urlArr)) {

            foreach ($urlArr as $key => $item) {
                $urlCheck .= $item . '/';
                // loại bỏ kí tự cuối cùng
                $fileCheck = rtrim($urlCheck, '/');
                $fileArr = explode('/', $fileCheck);
                // chuyển đổi chữ cái đầu tiên ở phần từ cuối cùng trước khi nối mảng
                $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
                $fileCheck = implode('/', $fileArr);

                if (!empty($urlArr[$key - 1])) {
                    unset($urlArr[$key - 1]);
                }
                if (file_exists('app/controllers/' . ($fileCheck) . 'Controller.php')) {
                    $urlCheck = $fileCheck;
                    break;
                }
            }
            $urlArr = array_values($urlArr);
        }



        //   Xử lí Controller
        if (!empty($urlArr[0])) {
            $this->__controller = ucfirst($urlArr[0]);
        } else {
            $this->__controller = ucfirst($this->__controller);
        }

        $this->__controller .= 'Controller';


        if (empty($urlCheck)) {
            $urlCheck = $this->__controller;
        } else {
            $urlCheck = $this->__controller;
        }
        if (file_exists('app/controllers/' . $urlCheck . '.php')) {
            require_once 'controllers/' . $urlCheck . '.php';

            // Kiểm tra class $this->__controller tồn tại
            if (class_exists($this->__controller)) {
                $this->__controller = new $this->__controller();
                unset($urlArr[0]);
                
                if(!empty($this->__db)) {
                    $this->__controller->db = $this->__db; // Global Query Builder
                }
              
            } else {
                $this->loadError();
            }
        } else {
            $this->loadError();
        }

        // Xử lí action
        if (!empty($urlArr[1])) {
            $this->__action = $urlArr[1];
            unset($urlArr[1]);
        }

        // Xử lí params
        $this->__params = array_values($urlArr);
        if (method_exists($this->__controller, $this->__action)) {
            call_user_func_array([$this->__controller, $this->__action], $this->__params);
        } else {
            $this->loadError();
        }
    }

    public function loadError($name = '404', $data = [])
    {
        extract($data);
        require_once 'error/' . $name . '.php';
    }

    public function handleRouteMiddleWare($routeKey, $db)
    {
        global $config;
        $routeKey = trim($routeKey);
        // Middleware App
        if (!empty($config['app']['routeMiddleware'])) {
            $routeMiddleWareArr = $config['app']['routeMiddleware'];
            foreach ($routeMiddleWareArr as $key => $middleWareItem) {
                if ($routeKey == trim($key) && file_exists('app/middlewares/' . $middleWareItem . '.php')) {
                    require_once 'app/middlewares/' . $middleWareItem . '.php';
                    if (class_exists($middleWareItem)) {
                        $middleWareOject = new $middleWareItem();
                        if (!empty($db)) {
                            $middleWareOject->db = $db;
                        }
                        $middleWareOject->handle();
                    }
                }
            }
        }
    }

    public function handleGlobalMiddleWare($db)
    {
        global $config;
        if (!empty($config['app']['globalMiddleware'])) {
            $globalMiddleWareArr = $config['app']['globalMiddleware'];
            foreach ($globalMiddleWareArr as $key => $middleWareItem) {
                if (file_exists('app/middlewares/' . $middleWareItem . '.php')) {
                    require_once 'app/middlewares/' . $middleWareItem . '.php';
                    if (class_exists($middleWareItem)) {
                        $middleWareOject = new $middleWareItem();
                        if (!empty($db)) {
                            $middleWareOject->db = $db;
                        }
                        $middleWareOject->handle();
                    }
                }
            }
        }
    }

    public function handleAppSeviceUser($db)
    {
        global $config;
        if (!empty($config['app']['boot'])) {
            $serviceUserArr = $config['app']['boot'];
            foreach ($serviceUserArr as $serviceName) {
                if (file_exists('app/core/' . $serviceName . '.php')) {
                    require_once 'app/core/' . $serviceName . '.php';
                    if (class_exists($serviceName)) {
                        $serviceOject = new $serviceName();
                        if (!empty($db)) {
                            $serviceOject->db = $db;
                        }
                        $serviceOject->boot();
                    }
                }
            }
        }
    }
}
