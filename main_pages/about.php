<?php
require_once '../class/Login.php';
Login::setSession();
$is_logged = Login::isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once '../main_layout/head.php' ?>
    <title>אודות - עד המדינה</title>
</head>
<body>
<div class="flex-container">
    <header>
        <?php include_once '../main_layout/header.php' ?>
    </header>
    <main class="main">
        <div class="title">
            <a href="" class="title">אודות</a>
        </div>

    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>

</body>
</html>