<?php
//require 'classes/DB.php';
require '../class/Login.php';
$tokenValid = false;
if($userId = Login::isLoggedIn()){
    if(isset($_POST['changepassword'])){
        if(isset($_POST['oldpassword']) && isset($_POST['newpassword']) && isset($_POST['newpassword2'])){
            $old_password = $_POST['oldpassword'];
            $new_password = $_POST['newpassword'];
            $new_password2 = $_POST['newpassword2'];
            $if = Login::query('SELECT password FROM users WHERE id=:userid', [':userid'=>$userId])->fetch(PDO::FETCH_ASSOC);
            if(password_verify($old_password,$if['password'])){
                if($new_password === $new_password2){
                    if(strlen($new_password) >= 6 && strlen($new_password) <= 60){
                        Login::query('UPDATE users SET password=:newpassword WHERE id=:userid',[':userid'=>$userId,':newpassword'=>password_hash($new_password, PASSWORD_BCRYPT)]);
                        echo "password changed!";
                    }
                }
            }else {
                echo "incorrect password";
            }
        }else {
            echo "please insert all fields0";
        }
    }

}else{
    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $hashed_token = sha1($token);
        $stmt = Login::connect()->prepare('SELECT * FROM forget_tokens WHERE token=?');
        $stmt->bindParam(1,$hashed_token,PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetch();
        if($userId){
            $tokenValid = true;
            $userId = $userId['uid'];
            if(isset($_POST['changepassword'])){
                if(isset($_POST['newpassword']) && isset($_POST['newpassword2'])){
                    $new_password = $_POST['newpassword'];
                    $new_password2 = $_POST['newpassword2'];
                    if($new_password === $new_password2){
                        if(strlen($new_password) >= 6 && strlen($new_password) <= 60){
                            Login::query('UPDATE front_users SET password=:newpassword WHERE id=:userid',[':userid'=>$userId,':newpassword'=>password_hash($new_password, PASSWORD_BCRYPT)]);
                            echo "password changed1!";
                            Login::query('DELETE FROM forget_tokens WHERE uid=:userid',[':userid'=>$userId]);
                        }else{
                            echo "password too short";
                        }
                    }
                }else {
                    echo "please insert all fields1";
                }
            }
        }else exit ("token invalid!");


    }else header('location:login.php');
}


?>

<!doctype html>
<html lang="en">
<head>
    <?php include_once '../main_layout/head.php' ?>
</head>
<body>
<div class="flex-container">
    <header>
        <?php include_once '../main_layout/header.php' ?>
    </header>
    <main class="main">
        <h1>Change Your Password</h1>
        <form action="<?= !$tokenValid?'changing-password.php':'changing-password.php?token='.$token?>" method="post">
            <?= !$tokenValid ? '<input type="password" name="oldpassword" placeholder="Current Password"><p />' : ''?>
            <input type="password" name="newpassword" placeholder="New Password ..."> <p />
            <input type="password" name="newpassword2" placeholder="Repeat Password ..."> <p />
            <input type="submit" name="changepassword" value="Change Password"> <p />
        </form>
    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>


</body>
</html>