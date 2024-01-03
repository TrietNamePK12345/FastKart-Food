<?php 
class HomeModel extends Model{
        private $__table = 'users';


    function tableFill()
    {
        return 'users';
    }

    function fieldFill(){
        return '*';  
    }

    function primaryKey(){
        return 'id';
    }

    public function insertUser($data)
    {
        $this->db->table('users')->insert($data);
    }

    public function updateUser($data, $id)
    {
        $this->db->table('users')->where('id','=', $id)->update($data);
    }

    public function deleteUser($id)
    {
        $this->db->table('users')->where('id','=', $id)->delete(); // nếu mày bỏ cái này : ->where('id','=', $id) thì đi luôn cả bảng =))
    }

    public function insertGetLast($data)
    {
        $this->db->table('users')->insert($data);
        return $this->db->lastId();
    }

 
}

?>