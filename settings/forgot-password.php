<?php
require '../class/login.php';
require '../includes/mailer.config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/src/Exception.php';
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
$msg = '';
if($submit = isset($_POST['reset_password'])){
    $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
    if($email){
        $token = Login::generateToken();
        $id_query = Login::query('SELECT id FROM front_users WHERE email=:email',[':email'=>$email]);
        if($id_query && ($userId = $id_query->fetch(PDO::FETCH_ASSOC))){
            $userId = $userId['id'];
            $exist_id = Login::query('SELECT uid FROM forget_tokens WHERE uid=:user_id',[':user_id'=>$userId])->fetch(PDO::FETCH_ASSOC);
            if(!$exist_id) {
                Login::query('INSERT INTO forget_tokens VALUES(\'\',:token,:user_id)',[':token'=>sha1($token),':user_id'=>$userId]);
                try{
                $mail = new PHPMailer(true);
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPSecure = 'tls';
                    $mail->SMTPAuth = true;
                    $mail->Username = $user_name;
                    $mail->Password = $pass;
                    $mail->Port = 587;

                    $mail->setFrom('haim3307@aadhamedina.com', 'Aadhamedina');
                    $mail->addAddress($email, 'עד המדינה');

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'aad-hamedina - reset password';
                    $msg1 = "<a href='".DOMAIN."settings/changing-password.php?token=$token'>". DOMAIN."settings/changing-password.php?token=$token</a><br>";
                    $mail->Body = ' אנא גש לקישור הבא על מנת לאפס את סיסמתך: '. $msg1;
                    $mail->send();
                    $msg = "נשלח איפוס לאימייל שציינת!";
                } catch (Exception $e) {
                    echo 'שליחה נכשלה:', $mail->ErrorInfo;
                }
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