<?php
require '../class/login.php';
$msg = '';
if($submit = isset($_POST['reset_password'])){
    $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    if($email){
        $token = Login::generate_token();
        $id_query = Login::query('SELECT id FROM front_users WHERE email=:email',[':email'=>$email]);
        if($id_query && ($userId = $id_query->fetch(PDO::FETCH_ASSOC))){
            $userId = $userId['id'];
            $exist_id = Login::query('SELECT * FROM forget_tokens WHERE uid=:user_id',[':user_id'=>$userId])->fetch(PDO::FETCH_ASSOC);
            echo "<br>$token<br>";
            if(!$exist_id) {
                Login::query('INSERT INTO forget_tokens VALUES(\'\',:token,:user_id)',[':token'=>sha1($token),':user_id'=>$userId]);
                $msg = "נשלח איפוס לאימייל שציינת!";
                $msg.= "<a href='./changing-password.php?token=$token'>כנס לאיפוס</a>";
            }else {
                $msg = "שליחה נכשלה";
            }
        }else $msg = "אימייל זה אינו רשום במערכת!";
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
        <h1>שכחת סיסמה ?</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="מה האימייל שנרשמת איתו?"><p />
            <input type="submit" name="reset_password" value="שלח איפוס לאימייל"> <p />
            <?= isset($email) && !$email && $submit? "אנא מלא את שדה האימייל":''?>
            <?= $msg ?>
        </form>


    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>


</body>
</html>