<nav class="nMenu">
    <div class="logoN">
        <div class="mobileB">
            <i class="fa fa-bars" style="color: white; font-size: 2em;" aria-hidden="true"></i>
        </div>
        <div class="logoNI"><img src="_img/layout/logo3.png"></div>
    </div>
    <ul class="navButtons">

        <li><a href="index.php">דף הבית</a></li>
        <li><a href="titles.php"> כותרות </a></li>
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
</div>
<?php else:?>
    <div class="userNav" style="float: left; text-align: left; padding: 20px">
        <div> שלום  <strong><?= $_SESSION['front_user_name'] ?></strong></div>
        <a href="index.php?act=logout">התנתק</a>
    </div>
<?php endif;?>

<div class="menuBanner">
    <div class="exitButton"><a href="#">X</a></div>
    <img class="menuBannerImg" src="_img/layout/menuBanner.png">
</div>
<marquee class="minutesReports" scrollamount="5" onmouseover="this.stop()" onmouseout="this.start()">2017 !ברוכים הבאים
    ל-עד המדינה
</marquee>