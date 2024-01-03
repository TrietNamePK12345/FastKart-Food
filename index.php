<?php
require_once  __DIR__.'/vendor/autoload.php';
echo '<pre>';
print_r(__DIR__.'/vendor/autoload.php');
echo '</pre>';

use app\core\Application;

$app = new Application();

$app->router->get('/', function(){
   return 'Hello World';
});

$app->router->get('/contact', function(){
    return 'This is contact';
});


$app->run();