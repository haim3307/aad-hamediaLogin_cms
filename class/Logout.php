<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:13
 */
require_once 'Login.php';
/*setcookie('SNID', '1', time() - 3600, PATH_FOLDER);
var_dump(DOMAIN);*/

class Logout extends Login
{
    static private function kill_cookies(){
        setcookie('SNID', 1, time() - 3600, '/', NULL, NULL, true);
        setcookie('SNID_', 1, time() - 3600, '/', NULL, NULL, true);
    }
    static function execute()
    {
        echo '<pre style="direction: ltr;">';
        var_dump($_POST);
        var_dump(isset($_POST['alldevices']) && $_POST['alldevices']);
        var_dump(Login::isLoggedIn());
        echo "<br>";
        var_dump(filter_input(INPUT_POST,'alldevices',FILTER_VALIDATE_INT));
        echo 'err';
        echo '</pre>';
        if (filter_input(INPUT_POST,'alldevices',FILTER_VALIDATE_INT)) {
            $d_all = self::query('DELETE FROM login_tokens WHERE uid=:userid', [':userid' => Login::isLoggedIn()]);
            var_dump($d_all);
            var_dump($d_all->rowCount());
        } else {
            if (isset($_COOKIE['SNID'])) {
                echo '1';
                $q = self::query('DELETE FROM login_tokens WHERE token=:token', [':token' => sha1($_COOKIE['SNID'])]);
            }

        }
        parent::setSession();
        session_destroy();
        self::kill_cookies();
        header('location:'.DOMAIN);
    }
}

Logout::execute();