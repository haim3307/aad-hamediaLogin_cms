<?php
$is_local = $_SERVER['HTTP_HOST'] === 'localhost';
$path = $is_local?'/aad-hamediaLogin_cms/dbManager':'/dbManager';
if (!isset($_GET['act'])) {
    //echo 'yes';
    if (isset($_POST['user_remember'])) session_set_cookie_params(60 * 60 * 24 * 7,$path);
}
session_set_cookie_params(null,$path);
session_start();


/*var_dump($_POST);*/
/*
*/

