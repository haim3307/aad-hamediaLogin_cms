<?php
if(!isset($_GET['act'])){
    //echo 'yes';
    if(isset($_POST['user_remember'])) session_set_cookie_params(60*60*24*7);
    session_start();
}




/*var_dump($_POST);*/
/*
*/

