<?php
//require 'classes/DB.php';
require '../class/Login.php';
$tokenValid = false;
$pass_exp = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';

if($userId = Login::isLoggedIn()){
    if(isset($_POST['change_password'])){
        if(isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['new_password2'])){
            $old_password = filter_input(INPUT_POST,'old_password',FILTER_SANITIZE_STRING);
            if(!($old_password = trim($old_password))) exit;
            $new_password = filter_input(INPUT_POST,'new_password',FILTER_SANITIZE_STRING);
            if(!($new_password = trim($new_password))) exit;
            if($new_password !== $_POST['new_password2']) exit;
            if(!preg_match($pass_exp,$new_password)){
                $error = 'הסיסמה החדשה אינה תקינה';
            }
            if(!isset($error)){
                $if_q = Login::query('SELECT password FROM front_users WHERE id=:userid', [':userid'=>$userId]);
                if($if_q && ($if = $if_q->fetch(PDO::FETCH_ASSOC))){
                    if(password_verify($old_password,$if['password'])){
                        Login::query('UPDATE front_users SET password=:new_password WHERE id=:userid',[':userid'=>$userId,':new_password'=>password_hash($new_password, PASSWORD_BCRYPT)]);
                        echo "סיסמא שונתה בהצלחה!";
                    }else {
                        $error = 'סיסמך שגויה או שאינה תקינה';
                    }
                }
            }


        }else {
            $error = '0אנא מלא את כל השדות';
        }
    }

}else{
    if($token = filter_input(INPUT_GET,'token',FILTER_SANITIZE_STRING)){
        $hashed_token = sha1($token);
        $stmt = Login::connect()->prepare('SELECT uid FROM forget_tokens WHERE token=?');
        $stmt->bindParam(1,$hashed_token,PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetch();
        if($userId){
            $tokenValid = true;
            $userId = $userId['uid'];
            if(isset($_POST['change_password'])){
                if(isset($_POST['new_password']) && isset($_POST['new_password2'])){
                    $new_password = filter_input(INPUT_POST,'new_password',FILTER_SANITIZE_STRING);
                    if(!($new_password = trim($new_password))) exit;
                    if($new_password !== $_POST['new_password2']) exit;
                    if(!preg_match($pass_exp,$new_password)){
                        $error = 'הסיסמה החדשה אינה תקינה';
                    }
                    if(!isset($error)){
                        Login::query('UPDATE front_users SET password=:new_password WHERE id=:userid',[':userid'=>$userId,':new_password'=>password_hash($new_password, PASSWORD_BCRYPT)]);
                        $msg =  'סיסמא שונתה בהצלחה!';
                        Login::query('DELETE FROM forget_tokens WHERE uid=:userid',[':userid'=>$userId]);
                    }
                }else {
                    $error = 'אנא מלא את כל השדות';
                }
            }
        }else header('location:../index.php');


    }else header('location:../index.php');
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
        <h1>החלפת סיסמה</h1>
        <form action="<?= !$tokenValid?'changing-password.php':'changing-password.php?token='.$token?>" method="post">
            <?= !$tokenValid ? '<input type="password" name="old_password" placeholder="סיסמא נוכחית"><p />' : ''?>
            <input type="password" name="new_password" placeholder="סיסמה חדשה ..."> <p />
            <span class="inputNote">*חייבת להכיל לפחות אות אחת גדולה,אותה קטנה,מספר אחד ומעל ל7 תווים</span><p />
            <input type="password" name="new_password2" placeholder="חזור על הסיסמה ..."> <p />
            <input type="submit" name="change_password" value="שנה סיסמא"> <p />
            <?= isset($error)?$error:'' ?>
            <?= isset($msg)?$msg:'' ?>
        </form>
    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>


</body>
</html>