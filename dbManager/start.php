<?php
require_once 'real_path.inc.php';
$is_local = $_SERVER['HTTP_HOST'] === 'localhost';
$path = $is_local?'/aad-hamediaLogin_cms/dbManager':'/dbManager';
if(isset($_GET['act'])){
    if($_GET['act'] !== 'logout'){
        if(isset($_POST['user_remember'])) session_set_cookie_params(60*60*24*7,$path);
    }
}
session_set_cookie_params(null,$path);
session_start();
//if(isset($_SESSION['loggedIN']) && $_SESSION['loggedIN'] == 1) header('location:index.php');
if(isset($_POST['login']) && isset($_POST['user_remember'])){
    setcookie(session_name(), "", time()-3600,$path);
    setcookie(session_name(),session_id(),time()+60*60*24*7,$path);
}
if(isset($_POST['login']) && isset($_POST['user_name']) && isset($_POST['user_pass'])){
    echo 'login';

    $user_name = $_POST['user_name'];
    $user_pass = $_POST['user_pass'];
    require 'class/formLogin.php';
    $users = new CMS_forms();
    if(!empty($user_name) && !empty($user_pass)){
        $data = $users->getUser($user_name,$user_pass);
        /*            echo '<pre>';
                    var_dump($data);
                    var_dump($data->rowCount());
                    var_dump($data->fetch());
                    echo '</pre>';*/
        if($data->fetch()) {
            $_SESSION['loggedIN'] = 1;
            $_SESSION['userName'] = $user_name;
            if(!empty($remember)) $_SESSION['remember'] = 1;
            header('location:index.php');
        }
        else{
            $error = "שם המשתמש או בסיסמא אינם תואמים";
        }
    }else{
        $error = 'אנא מלא את כל השדות';

    }
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
            <form action="" method="post" style="height: 100%;" novalidate>
                <div role="group" id="loginForm">
                    <input id="userName" name="user_name" placeholder="שם משתמש" required>
                    <input type="password" name="user_pass" id="userPass" placeholder="סיסמה" required>
                    <div>
                        <label style="color:black; font-family:'arial'" for="rememberMe">זכור אותי</label>
                        <input type="checkbox" name="user_remember" id="rememberMe">
                    </div>
                    <input type="submit" name="login" value="היכנס" id="loginButton">
                </div>
                <div class="logErrors jsMessage">
                    <div class="logError" style="text-align: center;">
                        <?= isset($error)?$error:'' ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
