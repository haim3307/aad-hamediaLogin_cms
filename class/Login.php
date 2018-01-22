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
        if (isset($userId)) {
            $token = self::generate_token();
            parent::query('INSERT INTO login_tokens VALUES(\'\',:token,:user_id)', [':token' => sha1($token), ':user_id' => $userId]);
            setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/aad-hamediaLogin_cms', NULL, NULL, true);
            setcookie("SNID_", 1, time() + 60 * 60 * 24 * 3, '/aad-hamediaLogin_cms', NULL, NULL, true);
        }
    }

    static function isLoggedIn()
    {
        if (isset($_COOKIE['SNID'])) {
            if ($query = parent::query(
                'SELECT uid FROM login_tokens WHERE token=:token',
                [':token' => sha1($_COOKIE['SNID'])])->fetch(PDO::FETCH_ASSOC)
            ) {
                $userId = $query['uid'];
                if (!isset($_COOKIE['SNID_'])) {
                    self::create_login_cookies(null, $userId);
                }
                return $userId;
            }
        }
        return false;
    }
    static public function login()
    {
        self::set_session();
        if (!isset($_POST['user_name'])) {
            return;
        }
        if (!isset($_POST['user_pass'])) {
            return;
        }
        session_id();
        $uname = $_POST['user_name'];
        $upass = $_POST['user_pass'];
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
                    $_SESSION['front_user_name'] = $uname;
                    $_SESSION['front_user_id'] = $user['id'];
                    $_SESSION['front_profile_img'] = $user['profile_img'];
                    $_SESSION['loggedInBlog'] = 1;
                    self::create_login_cookies($_SESSION['front_user_name'],$_SESSION['front_user_id']);
                }
            }
        }


    }
    static function logout()
    {
        /*        if(isset($_POST['confirm'])){        }*/

        if (isset($_POST['alldevices'])) {
            parent::query('DELETE FROM login_tokens WHERE uid=:userid', [':userid' => Login::isLoggedIn()]);
        } else {
            if (isset($_COOKIE['SNID'])) {
                echo '1';
                $q = parent::query('DELETE FROM login_tokens WHERE token=:token', [':token' => sha1($_COOKIE['SNID'])]);
            }

        }
        setcookie('SNID', '1', time() - 3600, '/aad-hamediaLogin_cms');
        setcookie('SNID_', '1', time() - 3600, '/aad-hamediaLogin_cms');
        setcookie(session_name(), '1', time() - 3600);

        session_start();
        session_destroy();
    }
}