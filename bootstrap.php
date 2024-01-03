<?php 
define('_DIR_ROOT', __DIR__);

if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){
    $web_root = 'https://'.$_SERVER['HTTP_HOST'];
}else{
    $web_root = 'http://'.$_SERVER['HTTP_HOST'];
}
$dir_root = str_replace('\\', '/', _DIR_ROOT); // dành cho Window

$documentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

$folder = str_replace(strtolower($documentRoot), '',strtolower($dir_root));

$web_root .= $folder;

define('_WEB_ROOT', $web_root);

/**
 * Tự độnng load configs
 */

 $config_dir = scandir('configs');

 if(!empty($config_dir)){
    foreach ($config_dir as $item) {
        if ($item!='.' && $item!='..' && file_exists('configs/'.$item)) {
            require_once 'configs/'.$item;
        }
    }
 }

//  LOAD ALL SERVICE
if (!empty($config['app']['service'])) {
    $allService = $config['app']['service'];
    if (!empty($allService)) {
        foreach ($allService as $serviceName) {
            if(file_exists('app/core/'.$serviceName.'.php')) {
                require_once 'app/core/'.$serviceName.'.php';
            }
        }
    }
}

require_once 'core/ServiceUser.php'; // Load All Services

require_once 'core/View.php'; // Load View Share

require_once 'core/Load.php'; // Load Load

require_once 'core/Middlewares.php'; // Load Middlewares

require_once 'core/Route.php'; // Load Route

require_once 'core/Session.php'; // Load Sesstion

// check configs and load Database
if (!empty($config['database'])) {
    $db_config = array_filter($config['database']);
}

if (!empty($db_config)) {

    require_once 'core/Connection.php';

    require_once 'core/QueryBuilder.php';

    require_once 'core/Database.php';

    require_once 'core/DB.php'; // Global Query Builder

}

require_once 'core/Helper.php'; // Load core Helper

// LOAD ALL HELPERS
 $allHelpers = scandir('app/helpers');
 if(!empty($allHelpers)){
    foreach ($allHelpers as $item) {
        if ($item!='.' && $item!='..' && file_exists('app/helpers/'.$item)) {
            require_once 'app/helpers/'.$item;
        }
    }
 }

require_once 'app/App.php'; // Load Application

require_once 'core/Model.php'; // Load Model

require_once 'core/TemplateEngine.php'; // Load TemplateEngines

require_once 'core/Controller.php'; // load Controller 

require_once 'core/Request.php'; // load Request

require_once 'core/Response.php'; // load Response
?>