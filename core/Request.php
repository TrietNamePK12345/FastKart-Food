<?php

namespace app\core;

class Request
{
    public  function getPath() // Trả về đường dẫn của request
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/'; // biến toàn cục
        $position = strpos($path, '?'); // strpos:  tìm vị trí đầu tiên của một chuỗi con trong một chuỗi lớn.
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
        // subtr : sử dụng để trích xuất một phần của chuỗi
    }

    public  function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']); // đổi thành chữ thường
    }
}