<?php
require '../class/login.php';
$msg = '';
$email_full = !empty($_POST['email']);
if($submit = isset($_POST['resetpassword'])){
    if($email_full){
        $token = Login::generate_token();
        $id_query = Login::query('SELECT id FROM front_users WHERE email=:email',[':email'=>$_POST['email']]);
        if($id_query){
            $userId = $id_query->fetch(PDO::FETCH_ASSOC)['id'];
            $exist_id = Login::query('SELECT * FROM forget_tokens WHERE uid=:user_id',[':user_id'=>$userId])->fetch(PDO::FETCH_ASSOC);
            echo "<br>$token<br>";
            if(!$exist_id) {
                Login::query('INSERT INTO forget_tokens VALUES(\'\',:token,:user_id)',[':token'=>sha1($token),':user_id'=>$userId]);
                $msg = "token sent! $token";
            }else {
                $msg = "failed to send!";
            }
        }else $msg = "email is not signed!";
    }

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
        <h1>Forgot Password ?</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Your Email Account"><p />
            <input type="submit" name="resetpassword" value="Send reset email"> <p />
            <?= !$email_full && $submit? "Please fill email field.":''?>
            <?= $msg ?>
        </form>


    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>


</body>
</html>