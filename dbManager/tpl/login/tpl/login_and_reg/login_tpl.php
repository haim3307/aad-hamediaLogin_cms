<?php
    if(isset($_POST['login']) && isset($_POST['user_name']) && isset($_POST['user_pass'])){
        $user_name = $_POST['user_name'];
        $user_pass = $_POST['user_pass'];
        require '../../../../class/formLogin.php';
        $users = new Forms();
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