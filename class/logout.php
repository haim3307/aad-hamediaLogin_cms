<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:13
 */
require_once 'Connection.php';
class Logout extends Connection {
    static function kill_cookies(){
        setcookie('SNID', '1', time() - 3600, DOMAIN);
        setcookie('SNID_', '1', time() - 3600, DOMAIN);
        setcookie(session_name(), '1', time() - 3600, DOMAIN);
    }
    static  function execute()
    {
        /*        if(isset($_POST['confirm'])){        }*/

    if (isset($_POST['alldevices'])) {
    self::query('DELETE FROM login_tokens WHERE uid=:userid', [':userid' => Login::isLoggedIn()]);
    } else {
        if (isset($_COOKIE['SNID'])) {
            echo '1';
            $q = self::query('DELETE FROM login_tokens WHERE token=:token', [':token' => sha1($_COOKIE['SNID'])]);
        }

    }
    self::kill_cookies();
    parent::set_session();
    session_destroy();
    //header('location:'.DOMAIN);
    }
}
if(isset($_POST['logout'])) Logout::execute();