<?php
require_once __DIR__.'/../vendor/autoload.php'; // Autoload các Class khi chúng được dùng

use app\core\Application; // dùng namespace app\core => Ngắn gọn cho class Application, tránh xung đột

$app = new Application(dirname(__DIR__)); // tạo đối tượng
/**
 * Hàm dirname => lấy path thư mục cha của thư mục hiện tại
 * Hằng số __DIR__: đường dẫn tuyệt đối (chứa thư mục hiện tại)
 */

$app->router->get('/', 'home');

$app->router->get('/contact','contact');

$app->router->post('/contact', function (){
    return 'Tôi là kết quả của bạn và em Form';
});


$app->run();