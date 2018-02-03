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

    static function create_login_cookies($username = null, $userId = null)
    {
        if (isset($username)) $userId = parent::query('SELECT id FROM front_users WHERE name=:username',
            [':username' => $username])->fetchAll()[0]['id'];
        if (isset($userId) && ($if_there = parent::query(
                'SELECT token FROM login_tokens WHERE uid=:uid',[':uid'=>$userId])) && $if_there->rowCount() <= 2) {
            //var_dump($if_there);
            var_dump($if_there->rowCount());
            $token = self::generate_token();
            parent::query('INSERT INTO login_tokens VALUES(\'\',:token,:user_id)', [':token' => sha1($token), ':user_id' => $userId]);
            setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, DOMAIN, NULL, NULL, true);
            setcookie("SNID_", 1, time() + 60 * 60 * 24 * 3, DOMAIN, NULL, NULL, true);
            return true;
        }else{
            return false;
        }
    }

    static function isLoggedIn()
    {
        if (isset($_COOKIE['SNID']) && !isset($_SESSION['loggedInBlog'])) {
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
                        $_SESSION['front_user_name'] = $uname['name'];
                        $_SESSION['front_user_id'] = $userId;
                        $_SESSION['front_profile_img'] = $uname['profile_img'];
                        return $userId;
                    }

                }

            }
        }else if(isset($_SESSION['front_user_id'])){
            return $_SESSION['front_user_id'];
        }
        return false;
    }
    static public function login()
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
                    if(!self::create_login_cookies($uname,$user['id'])){
                        echo 'לא ניתן להכנס לאותו משתמש ביותר ממכשיר אחד בו זמנית';
                        return false;
                    }else{
                        $_SESSION['front_user_name'] = $uname;
                        $_SESSION['front_user_id'] = $user['id'];
                        $_SESSION['front_profile_img'] = $user['profile_img'];
                        $_SESSION['loggedInBlog'] = 1;
                    }
                    return true;
                }
            }else return false;
        }


    }
}