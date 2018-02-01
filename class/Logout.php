<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:13
 */
require_once 'Login.php';
class Logout extends Login {
    static function kill_cookies(){
        setcookie('SNID', '1', time() - 3600, DOMAIN);
        setcookie('SNID_', '1', time() - 3600, DOMAIN);
        setcookie(session_name(), '1', time() - 3600, DOMAIN);
    }
    static  function execute()
    {
        /*        if(isset($_POST['confirm'])){        }*/
echo '<pre style="direction: ltr;">';
        var_dump($_POST);
        var_dump(isset($_POST['alldevices']) && $_POST['alldevices']);
        var_dump(Login::isLoggedIn());
        echo 'err';
echo '</pre>';
    if (isset($_POST['alldevices']) && $_POST['alldevices']) {
    $d_all = self::query('DELETE FROM login_tokens WHERE uid=:userid', [':userid' => Login::isLoggedIn()]);
    var_dump($d_all);
    var_dump($d_all->rowCount());
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