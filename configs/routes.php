<?php 
$routes['default_controller'] = 'home';

/**
 * đường dẫn ảo -> đường dẫn thật.
 * 
 */
    $routes['dashboard'] = 'admin/dashboard';

    // Trang người dùng
    $routes['trang-chu'] = 'home/index';
    $routes['cua-hang'] = 'shop/index';
    $routes['chi-tiet-san-pham'] = 'shop/detail';


    // Đăng Nhập và Đăng Kí, Quên Mật Khẩu
    $routes['dang-nhap'] = 'admin/login';
    $routes['dang-ki'] = 'admin/register';
    $routes['quen-mat-khau'] = 'admin/forgot';

?>