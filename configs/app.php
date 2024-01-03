<?php 
$config['app'] = [
    'service' => [
        HtmlHelper::class
    ],
    'routeMiddleware' => [
        'danh-sach-san-pham' => AuthMiddleware::class,
    ],
    'globalMiddleware' => [
        ParamsMiddleware::class
    ],
    'boot' => [
        AppServiceUser::class
    ]
];
?>