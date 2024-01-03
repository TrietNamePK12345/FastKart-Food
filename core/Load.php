<?php 
class Load
{
    static public function model($model)
    {
        if(file_exists(_DIR_ROOT.'/app/models/'.$model.'.php')){
            require_once _DIR_ROOT.'/app/models/'.$model.'.php';
            if(class_exists($model)){
                $model = new $model();
                return $model;
            }
        }
        return false;
    }

    static public function view($view, $data = [])
    {
        if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
            if (!empty($data) && is_array($data)) {
                // hàm extract đổi các key của mảng thành biến.
                extract($data);
            }
            require_once _DIR_ROOT . '/app/views/' . $view . '.php';
        }
    }
}

?>