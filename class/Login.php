<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 20/01/2018
 * Time: 16:35
 */
require_once 'Connection.php';
class Login extends Connection
{
    function __construct()
    {
        parent::__construct();
    }

    static function generate_token($hash = false)
    {
        $csstrong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $csstrong));
        return !$hash ? $token : sha1($token);
    }
    static function csrf_token(){
        return sha1('AadHamedina'.rand(1,1000).date('H.m.s'));
    }
    static function generate_c_name_id($name){
        return $name.sha1(rand(1,1000));
    }
    static function create_login_cookies($username = null, $userId = null)
    {
        if (isset($username)) $userId = parent::query('SELECT id FROM front_users WHERE name=:username',
            [':username' => $username])->fetchAll()[0]['id'];
        if (isset($userId) && ($if_there = parent::query(
                'SELECT token FROM login_tokens WHERE uid=:uid',[':uid'=>$userId])) && $if_there->rowCount() <= 5) {
            //var_dump($if_there);
            //var_dump($if_there->rowCount());
            $token = self::generate_token();
            parent::query('INSERT INTO login_tokens VALUES(\'\',:token,:user_id,:c_name,NOW())',
                [':token' => sha1($token), ':user_id' => $userId,':c_name'=>'SNID']);
            setcookie('SNID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
            setcookie('SNID_' , 1, time() + 60 * 60 * 24 * 3, '/', NULL, NULL, true);
            return true;
        }else{
            return false;
        }
    }
    static function regenerate_cookie(){

        if(isset($_COOKIE['SNID'])){
/*                        var_dump($_COOKIE['SNID']);
                        echo "<br>";

                        var_dump(DOMAIN);*/
            $token = self::generate_token();
            setcookie('SNID', 1, time() - 3600, '/', NULL, NULL, true);
            if(parent::query('UPDATE login_tokens SET token = :new_token WHERE token = :token',
                [':token' => sha1($_COOKIE['SNID']),':new_token' => sha1($token)])){
                setcookie('SNID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
            }
        }
    }
    static function isLoggedIn()
    {
        if (isset($_COOKIE['SNID']) && !isset($_SESSION['loggedInBlog']))
        {//if user enters after closing and reopening browser
            if ($query = parent::query(
                'SELECT uid FROM login_tokens WHERE token=:token',
                [':token' => sha1($_COOKIE['SNID'])])->fetch(PDO::FETCH_ASSOC)
            ) {//check if the cookie token is inside DB and bring user id
                if(!isset($_POST['logout']))self::regenerate_cookie();
                $userId = $query['uid'];

                if(!isset($_SESSION)) self::set_session();//if session haven't started yet do it now
                else {//if its already set - verify the client
                    self::verify_user_ip_and_agent();
                }
                if($uname_q = parent::query(
                    'SELECT name,profile_img FROM front_users WHERE id=:id',
                    [':id' => $userId])){//query user data
                    if($uname = $uname_q->fetch()){
                        if (!isset($_COOKIE['SNID_'])) {//if second cookie isn't set, it means 3 days passed since last login
                            if(!self::create_login_cookies(null, $userId)){//so create new cookies to long expire time
                                echo 'לא ניתן להכנס לאותו משתמש ביותר ממכשיר אחד בו זמנית';//if it failed's it means user is connected on to many devices
                            }
                        }
                        //if all user data is ok add it to the session
                        self::set_session_user_verify($uname['name'],$userId,$uname['profile_img']);

                        return $userId;
                    }

                }

            }
        }else if(isset($_SESSION['front_user_id']) && isset($_COOKIE['SNID']))
        {//else if your is all ready logged in return me the id
            if(self::verify_user_ip_and_agent()){
                return $_SESSION['front_user_id'];
            }
        }
        return false;
    }
    static public function login()
    {
        self::set_session();
        var_dump($_SESSION['token']);
        echo "<br>";

        var_dump($_POST['token']);
        if(isset($_POST['token'])){
            if($_SESSION['token'] !== $_POST['token']) return false;
        }else return false;
        $uname = filter_input(INPUT_POST,'user_name',FILTER_SANITIZE_STRING);
        $uname = trim($uname);
        $upass = filter_input(INPUT_POST,'user_pass',FILTER_SANITIZE_STRING);
        $upass = trim($upass);
        if (!$uname) {
            return;
        }
        if (!$upass) {
            return;
        }
        //session_id();
        if($get_pass = $stmt = self::query(
            "SELECT password FROM front_users WHERE name=:name",
            [':name' => $uname]
        )){
            $db_pass = $get_pass->fetch(PDO::FETCH_ASSOC)['password'];
            if(password_verify($upass, $db_pass)){
                $stmt = self::query(
                    "SELECT id,name,profile_img FROM front_users WHERE name=:name",
                    [':name' => $uname]
                );
                if ($stmt && $stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
/*                    $_SESSION['c_name'] = self::generate_c_name_id('SNID');*/
                    if(!self::create_login_cookies($uname,$user['id'])){
                        echo 'לא ניתן להכנס לאותו משתמש ביותר ממכשיר אחד בו זמנית';
                        return false;
                    }else{
                        self::set_session_user_verify($uname,$user['id'],$user['profile_img']);
                    }
                    return true;
                }
            }else return false;
        }


    }
    static function isLoggedInOld()
    {
        /*
        if (!isset($_SESSION['loggedInBlog'])) {
            if ($query = parent::query(
                'SELECT uid FROM login_tokens WHERE token=:token',
                [':token' => sha1($_COOKIE['SNID'])])->fetch(PDO::FETCH_ASSOC)
            ) {
                $userId = $query['uid'];
                if($uname_q = parent::query(
                    'SELECT name,profile_img FROM front_users WHERE id=:id',
                    [':id' => $userId])){
                    if($uname = $uname_q->fetch()){
                        if (!isset($_COOKIE['SNID_'])) {
                            if(!self::create_login_cookies(null, $userId)){
                                echo 'לא ניתן להכנס לאותו משתמש ביותר ממכשיר אחד בו זמנית';
                            }
                        }
                        if(!isset($_SESSION)) self::set_session();
                        self::set_session_user_verify($uname['name'],$userId,$uname['profile_img']);

                        return $userId;
                    }

                }

            }
        }
        */
        if(!isset($_SESSION)) self::set_session();
        if(isset($_SESSION['front_user_id'])){
            return $_SESSION['front_user_id'];
        }
        return false;
    }
    static public function login1Old()
    {
        self::set_session();
        $uname = filter_input(INPUT_POST,'user_name',FILTER_SANITIZE_STRING);
        $uname = trim($uname);
        $upass = filter_input(INPUT_POST,'user_pass',FILTER_SANITIZE_STRING);
        $upass = trim($upass);
        if (!$uname) {
            return;
        }
        if (!$upass) {
            return;
        }
        //session_id();
        if($get_pass = $stmt = self::query(
            "SELECT password FROM front_users WHERE name=:name",
            [':name' => $uname]
        )){
            $db_pass = $get_pass->fetch(PDO::FETCH_ASSOC)['password'];
            if(password_verify($upass, $db_pass)){
                $stmt = self::query(
                    "SELECT id,name,profile_img FROM front_users WHERE name=:name",
                    [':name' => $uname]
                );
                if ($stmt && $stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
/*                    if(!self::create_login_cookies($uname,$user['id'])){
                        echo 'לא ניתן להכנס לאותו משתמש ביותר ממכשיר אחד בו זמנית';
                        return false;
                    }else{
                    }*/
                    self::set_session_user_verify($uname,$user['id'],$user['profile_img']);
                    return true;
                }
            }else return false;
        }


    }
    static private function set_session_user_verify($uname,$uid,$u_img){
        if(isset($_SESSION)){
            $_SESSION['front_user_name'] = $uname;
            $_SESSION['front_user_id'] = $uid;
            $_SESSION['front_profile_img'] = $u_img;
            $_SESSION['loggedInBlog'] = 1;
            $_SESSION['front_user_ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['front_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            return true;
        }else return false;
    }
    static private function verify_user_ip_and_agent(){
        $verify = false;
        if(isset($_SESSION['front_user_ip']) && $_SESSION['front_user_ip'] == $_SERVER['REMOTE_ADDR'] ){
            if(isset($_SESSION['front_user_agent']) && $_SESSION['front_user_agent'] == $_SERVER['HTTP_USER_AGENT']){
                $verify = true;
            }
        }
        return $verify;
    }
}
