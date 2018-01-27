<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 25/01/2018
 * Time: 22:06
 */
if ($follower_id = (int)Login::isLoggedIn()) {

    if (isset($_GET['username'])) {
        if ($userIdQ = Login::query('SELECT name,id FROM front_users WHERE name=:username', [':username' => $_GET['username']])) {
            $res_user = $userIdQ->fetch(PDO::FETCH_ASSOC);
            $user_name = $res_user['name'];
            $user_id = (int)$res_user['id'];
            echo '<pre style="direction: ltr;">';
            var_dump($user_id);
            var_dump($follower_id);

            $is_following = Social_web::is_following($follower_id,$user_id);
            echo '</pre>';

        }
    }else{
        $user_name = $_SESSION['front_user_name'];
        $user_id = $_SESSION['front_user_id'];
    }
    if (isset($_POST['post'])) {
        $post_body = $_POST['postbody'];
        Login::query('INSERT INTO posts VALUES("",:post_body,NOW(),:user_id,0)', [':post_body' => $post_body, ':user_id' => $follower_id]);
    }
} else {
    header('location:login.php');
}

?>
<?php if (isset($user_name)): ?>
    <h1>הפרופיל של <?= $user_name ?></h1>
<?php if ($follower_id != $user_id): ?>

    <form action="" method="POST">
        <input type="button" name="follow" id="follow"
               value="<?= $follow_act = isset($is_following) && $is_following ? 'הסר עוקב' : 'עקוב' ?>">
    </form>
    <script>
        const $follow = $('#follow');
		$follow.on('click',function () {
            follow(<?= "'$follow_act'" ?>,<?= $user_id ?>);
		});
        function follow(f_act,uid) {
            $.ajax({
                url:'api/index.php',
                method: 'post',
                data: {
                	action:'follow',
                    uid,
                }
            }).then((res)=>{
            	if(res === 'true'){
                   $follow.val('הסר עוקב');
                }else if(res === 'false'){
                    $follow.val('עקוב');
                }else {
            		console.log(res);
                }

			});
        }
    </script>
<?php endif; ?>
<form action="" method="post">
    <textarea name="postbody" id="" rows="8" cols="80"></textarea>
    <p/>
    <input type="submit" name="post">
</form>

<?php else: ?>
<h1>המשתמש לא נמצא במערכת..</h1>
<?php endif; ?>


