<?php 
 $sessionKey = Session::isInvalid();
 $errors = Session::flash($sessionKey.'_errors');
 $old = Session::flash($sessionKey.'_old');

if (!function_exists('form_errors')) {
    function form_errors($fieldName, $before = '', $after = '')
    {
        global $errors;
        if (!empty($errors) && array_key_exists($fieldName, $errors)) {
            return $before.$errors[$fieldName].$after;
        }  
        return false; 
    }
}


if (!function_exists('old')) {
    function old($fieldName, $default = '') 
    {
        global $old;
        if (!empty($old[$fieldName])) {
            return $old[$fieldName];
        }
        return false;
    }
}

?>