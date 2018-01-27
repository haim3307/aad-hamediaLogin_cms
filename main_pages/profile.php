<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 25/01/2018
 * Time: 22:06
 */
require '../class/Login.php';

if ($follower_id = Login::isLoggedIn()) {
    echo 'logged In';

    if (isset($_GET['username'])) {
        if ($userIdQ = Login::query('SELECT username,id FROM users WHERE username=:username', [':username' => $_GET['username']])) {
            $res_user = $userIdQ[0];
            $user_name = $res_user['username'];
            $user_id = $res_user['id'];
            $is_following = Login::query('SELECT follower_id FROM followers WHERE user_id=:uid AND follower_id=:fid', [':uid' => $user_id, 'fid' => $follower_id]);
            if (isset($_POST['follow']) && $_POST['follow']) {

                if (!($is_following)) {
                    Login::query('INSERT INTO followers VALUES(\'\', :uid,:fid)', [':uid' => $res_user['id'], 'fid' => $follower_id]);
                } else {
                    Login::query('DELETE FROM followers WHERE user_id=:uid AND follower_id=:fid', [':uid' => $res_user['id'], 'fid' => $follower_id]);
                }
            }
        }
    }
    if (isset($_POST['post'])) {
        $post_body = $_POST['postbody'];
        Login::query('INSERT INTO posts VALUES("",:post_body,NOW(),:user_id,0)', [':post_body' => $post_body, ':user_id' => $follower_id]);
    }
} else {
    header('location:login.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php include_once '../main_layout/head.php' ?>
    <title>Document</title>
</head>
<body>
<div class="flex-container">
    <header>
        <?php include_once '../main_layout/header.php' ?>
    </header>
    <main class="main">
        <div class="title"><a href="index.php" class="title"> דף הבית</a></div>
        <?php if (isset($_GET['username'])): ?>
            <?php if (isset($user_name)): ?>
                <h1><?= $user_name ?>'s Profile</h1>
            <?php endif; ?>
            <?php if ($follower_id != $user_id): ?>

                <form action="" method="POST">
                    <input type="submit" name="follow"
                           value="<?= isset($is_following) && $is_following ? 'follow' : 'unfollow' ?>">
                </form>
            <?php endif; ?>
            <form action="" method="post">
                <textarea name="postbody" id="" rows="8" cols="80"></textarea>
                <p/>
                <input type="submit" name="post">
            </form>
        <?php endif; ?>
    </main>
</div>
</body>
</html>

