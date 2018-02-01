<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:13
 */
require_once 'Connection.php';
require_once '../includes/domain.php';
function logout()
{
    /*        if(isset($_POST['confirm'])){        }*/

    if (isset($_POST['alldevices'])) {
        Connection::query('DELETE FROM login_tokens WHERE uid=:userid', [':userid' => Login::isLoggedIn()]);
    } else {
        if (isset($_COOKIE['SNID'])) {
            echo '1';
            $q = Connection::query('DELETE FROM login_tokens WHERE token=:token', [':token' => sha1($_COOKIE['SNID'])]);
        }

    }
    setcookie('SNID', '1', time() - 3600, '/aad-hamediaLogin_cms');
    setcookie('SNID_', '1', time() - 3600, '/aad-hamediaLogin_cms');
    setcookie(session_name(), '1', time() - 3600);

    session_start();
    session_destroy();
    header('location:'.DOMAIN);
}
if(isset($_POST['logout'])) logout();