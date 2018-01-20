<?php
//require 'tpl/login/session.php';
if(isset($_GET['act'])){
    if($_GET['act'] !== 'logout'){
        if(isset($_POST['user_remember'])) session_set_cookie_params(60*60*24*7);
    }
}

//if(isset($_POST['user_remember'])) session_set_cookie_params(60*60*24*7);
session_start();
if(isset($_SESSION['loggedIN']) && $_SESSION['loggedIN'] == 1) header('location:index.php');
if(isset($_POST['login']) && isset($_POST['user_remember'])){
    setcookie(session_name(), "", time()-3600,'/');
    setcookie(session_name(),session_id(),time()+60*60*24*7,'/');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="tpl/login/styles/_css/styles.css">
    <style>
        body,html{
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="logNregPage">
        <nav class="topNav">
<!--            <ul>
                <li id="login"><a href="start.php?act=login">כניסה</a></li>
                <li id="register"><a href="start.php?act=register">הרשמה</a></li>
            </ul>-->
        </nav>
        <style>
            .innerForm{
                height: 100%;

            }
        </style>
        <div id="allForm" class="innerForm">
            <?php
                require 'tpl/login/tpl/login_and_reg/login_tpl.php';
            ?>
        </div>
    </div>
</body>
</html>
