<?php
$is_local = $_SERVER['HTTP_HOST'] === 'localhost';
$path = $is_local ? '/aad-hamediaLogin_cms/dbManager' : '/dbManager';
session_set_cookie_params(null, $path);
setcookie(session_name(), "", time() - 3600, $path);
session_start();
session_destroy();

header('Location:../../start.php');
exit('logged out!');
