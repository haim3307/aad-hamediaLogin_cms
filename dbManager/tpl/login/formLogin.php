<?php
require '../class/connection.php';
class Forms extends  Connection{
    public function getUsers($query){
        $query = $query || 'SELECT * FROM users';
        $result = parent::$con->query($query);
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    protected function clean_userData($uname, $upass){
        $uname = parent::$con->quote($uname);
        $upass = md5(md5(parent::$con->quote($upass)));
        $arr = ["userName" => $uname , "userPassword" => $upass];
        return $arr;
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
/*        echo '<pre>';
        var_dump($uname);
        var_dump($upass);
        var_dump($result);
        echo '</pre>';*/
        return $result;
    }

}
