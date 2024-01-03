<?php 
class AppServiceUser extends ServiceUser
{ 
    public function boot()
    {
        $dataUser = $this->db->table('user')->where('id', '=' ,1)->first();
        $data['userInfo'] = $dataUser;
        $data['copyright'] = 'Copyright &copy; 2024 by Monsaide';
        View::share($data);
    }   
}

?>