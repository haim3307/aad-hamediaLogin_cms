<?php
require '../../class/Connection.php';
class CMS_forms extends  Connection{
    public function getUsers($query){
        $query = $query || 'SELECT * FROM users';
        $result = parent::$con->query($query);
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    protected function encrypt_pass($upass){
        return md5(md5($upass));

    }
    public function getUser($uname,$upass){
        $upass = $this->encrypt_pass($upass);

        $result = parent::query(
            "SELECT id FROM users WHERE userName = :uname and password = :upass",
            [':uname'=>$uname,':upass'=>$upass]
        );

        return $result;
    }

}
