<?php
/*if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die (header( 'HTTP/1.0 403 Forbidden', TRUE, 403 ));
}*/
if(!isset($is_logged)){
    $is_logged = Login::isLoggedIn();
}
if (isset($_POST['site_login'])) {
    if(Login::login()) header('location:'.$_SERVER['PHP_SELF']);
    else $_SESSION['token'] = Login::csrf_token();
}else {
    Login::set_session();
    $_SESSION['token'] = Login::csrf_token();
}
?>
<nav class="nMenu">
    <div class="logoN">
        <div class="mobileB">
            <i class="fa fa-bars" style="color: white; font-size: 2em;" aria-hidden="true"></i>
        </div>
        <div class="logoNI"><img src="<?= DOMAIN ?>_img/layout/logo3.png"></div>
    </div>
    <ul class="navButtons">

        <li><a href="<?= DOMAIN ?>">דף הבית</a></li>
        <li><a href="#"> צרו קשר </a></li>
        <li><a href="#"> אודות </a></li>

    </ul>


</nav>
<style>
    #loginForm{
        display: flex;
        margin: 10px;
        height: 40px;
    }

    #submitLogin {
        padding: 10px;
        height: 100%;
    }
    .logInput{
        height: 100%;
    }
    @media (max-width: 810px) {
        #loginForm{
            display: flex;
            flex-flow: column;
            margin: 0;
            height: 200px;
        }
        #loginForm input[type="text"]{
        }
        .logInput{
            flex: 2;
            height: auto;

        }
        #submitLogin {
            padding: 10px;
            height: auto;
        }
    }
    .toRegister{
        padding: 0 20px; display: flex; justify-content: space-between; flex-direction:row-reverse;
    }
    #logoutMenu{
        display: none;
        position: absolute;
        left: 0;
        top:30px;
        background-color: #fff;
    }
    #logoutMTrigger a{
        text-decoration: none;
    }
    #logoutMTrigger a{
        text-decoration: none;
    }
    #logoutMenu {
        list-style: none;
    }
    #logoutMenu li{
        padding: 10px 2px;
        text-align: center;
    }
    #logoutMenu li:hover{
        background-color: silver;
        color: white;
    }
    #logoutMenu input{
    }
    .userNav{
        float: left; text-align: left; padding: 20px;
    }
</style>
<?php if(!$is_logged):?>
<div style="direction: ltr">
    <form style="direction: rtl;" action="" id="loginForm" method="POST" novalidate>
        <input type="text" name="user_name" class="logInput" placeholder="שם המשתמש">
        <input type="password" name="user_pass" class="logInput" placeholder="סיסמא">
        <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
        <input type="submit" name="site_login" id="submitLogin" value="התחבר">
    </form>
    <div class="forgotPass" style="direction: rtl;">
    </div>
    <div class="toRegister" style="">
        <a href="<?= DOMAIN ?>settings/forgot-password.php">שכחתי את הסיסמא</a>

        <div>
            <span>עדיין לא רשום?</span>
            <a href="<?= DOMAIN ?>main_pages/register_page.php">לחץ כאן</a>
        </div>
    </div>
</div>
<?php else:?>
    <div class="userNav" style="">
        <div> שלום  <strong><?= htmlentities($_SESSION['front_user_name']); ?></strong></div>
        <form id="logoutForm" action="<?= DOMAIN.'class/Logout.php' ?>" method="post" style="position: relative;">
            <input type="submit" name="logout" id="logoutI" style="display: none;">
            <label for="logoutI"><a>התנתק</a></label>
            <i class="fa fa-angle-down" id="logoutMTrigger">

            </i>
            <span id="logoutMenu" style="width: 200px">
                <ul>
                    <li><input type="radio" id="alldevicesN" name="alldevices" value="0"><label for="alldevicesN">ממכשיר זה</label></li>
                    <li><label for="alldevicesY"><input type="radio" id="alldevicesY" name="alldevices" value="1">מכל המכשירים</label></li>

                </ul>
            </span>
        </form>
        <script>
            $('#logoutMTrigger').on('click',function () {
                $(this).siblings('#logoutMenu').slideToggle(100);
			});
        </script>
    </div>
<?php endif;?>

<!--<div class="menuBanner">
    <div class="exitButton"><a href="#">X</a></div>
    <img class="menuBannerImg" style="max-height: 200px" src="<?/*= DOMAIN */?>_img/layout/menuBanner.png">
</div>-->
<marquee class="minutesReports" scrollamount="5" onmouseover="this.stop()" onmouseout="this.start()">2017 !ברוכים הבאים
    ל-עד המדינה
</marquee>