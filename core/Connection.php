<?php
class Connection
{
    private static $instance = null, $conn = null;

    private function __construct($config)
    {

        // Kết nối database
        try {
            // Cấu hình DSN
            $dsn = 'mysql:dbname=' . $config['db'] . ';host=' . $config['host'];

            $con = new PDO($dsn, $config['user'], $config['pass']);
            self::$conn = $con;
        } catch (Exception $exception) {
            $mess = $exception->getMessage();
            App::$app->loadError('Error_Database', ['message'=>$mess]);
            die();
        }
    }

    public static function getInstance($config)
    {
        if (self::$instance == null) {
            $connection = new Connection($config);
            self::$instance = self::$conn;
        }

        return self::$instance;
    }
}
