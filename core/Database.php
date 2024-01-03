<?php 
class Database{
    private $__conn;

    use QueryBuilder;

    function __construct(){
        global $db_config;
        $this->__conn = Connection::getInstance($db_config);
      
    }


    // thêm
    public function insertData($table, $data)
    {
        if (!empty($data)) {
            $fielStr = '';
            $valueStr = '';
            foreach ($data as $key => $value) {
                $fielStr .= $key . ',';
                $valueStr .= "'" . $value . "',";
            }

            $fielStr = rtrim($fielStr, ',');
            $valueStr = rtrim($valueStr, ',');
            $sql = "INSERT INTO  $table($fielStr) VALUES ($valueStr)";
            $status = $this->query($sql);
            if (!$status) return false;
        }
        return true;
    }

    // sửa
    public function updateData($table, $data, $condition = '')
    {
        if (!empty($data)) {
            $updateStr = '';
            foreach ($data as $key => $value) {
                $updateStr .= "$key='$value',";
            }
            $updateStr = rtrim($updateStr, ',');
            $sql = "UPDATE $table SET $updateStr";
            if (!empty($condition)) {
                $sql = "UPDATE $table SET $updateStr WHERE $condition";
            }
            $status = $this->query($sql);
            if (!$status) return false;
        }
        return true;
    }

    // xóa
    public function deleteData($table, $condition = ''): bool
    {
        $sql = 'DELETE FROM ' . $table;
        if (!empty($condition)) {
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
        }
        $status = $this->query($sql);
        if (!$status) return false;
        return true;
    }

    // truy vấn dữ liệu
    public function query($sql)
    {
           try
           {
            
            $statement =  $this->__conn->prepare($sql);
            $statement->execute();
            return $statement;

           }catch(Exception $exception)
           {

            $mess = $exception->getMessage();
            $data['message'] = $mess;
            App::$app->loadError('Error_Database', $data);
            die();

           }
          
        
         
    }


    // Trả về id mới nhất sau khi đã insert
    public function lastInsertId()
    {
        return  $this->__conn->lastInsertId();
    }
}


?>