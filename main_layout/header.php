<?php

if (isset($_POST['site_login'])) {
    if(Login::login()) header('location:'.$_SERVER['PHP_SELF']);

}else{
    Login::set_session();
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
        <li><a href="<?= DOMAIN ?>main_pages/titles.php"> כותרות </a></li>
        <li><a href="#"> פוליטיקה </a></li>
        <li><a href="#"> כלכלה </a></li>
        <li><a href="#"> טכנולוגיה </a></li>
        <li><a href="#"> העולם </a></li>
        <li><a href="#"> רכב </a></li>
        <li><a href="#"> צרו קשר </a></li>

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

</style>
<?php if(!isset($_SESSION['loggedInBlog'])):?>
<div style="direction: ltr">
    <form style="direction: rtl;" action="" id="loginForm" method="POST" novalidate>
        <input type="text" name="user_name" class="logInput" placeholder="שם המשתמש">
        <input type="text" name="user_pass" class="logInput" placeholder="סיסמא">
        <input type="submit" name="site_login" id="submitLogin" value="התחבר">
    </form>
    <div class="forgotPass" style="direction: rtl;">
    </div>
    <div class="toRegister" style="padding: 0 20px; display: flex; justify-content: space-between; flex-direction:row-reverse ">
        <a href="<?= DOMAIN ?>settings/forgot-password.php">שכחתי את הסיסמא</a>

        <div>
            <span>עדיין לא רשום?</span>
            <a href="<?= DOMAIN ?>main_pages/register_page.php">לחץ כאן</a>
        </div>
    </div>
</div>
<?php else:?>
    <div class="userNav" style="float: left; text-align: left; padding: 20px">
        <div> שלום  <strong><?= $_SESSION['front_user_name'] ?></strong></div>
        <form action="<?= DOMAIN.'class/logout.php' ?>" method="post">
            <input type="submit" name="logout" id="logoutI" style="display: none;">
            <label for="logoutI"><a>התנתק</a></label>
        </form>
    </div>
<?php endif;?>

<div class="menuBanner">
    <div class="exitButton"><a href="#">X</a></div>
    <img class="menuBannerImg" style="max-height: 200px" src="<?= DOMAIN ?>_img/layout/menuBanner.png">
</div>
<marquee class="minutesReports" scrollamount="5" onmouseover="this.stop()" onmouseout="this.start()">2017 !ברוכים הבאים
    ל-עד המדינה
</marquee>