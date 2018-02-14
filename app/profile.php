<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 25/01/2018
 * Time: 22:06
 */
if ($follower_id = Login::isLoggedIn()) {

    if (isset($_GET['username']) || isset($_GET['userid'])) {
        $user_name = filter_input(INPUT_GET,'username',FILTER_SANITIZE_STRING);
        $user_id = filter_input(INPUT_GET,'userid',FILTER_VALIDATE_INT);
        if($user_name || $user_id){
            $userIdQ = Login::connect()->prepare('SELECT name,id FROM front_users WHERE '.($user_name?'name=:username':'id=:userid'));
            if($user_name) $userIdQ->bindParam(':username',$user_name,PDO::PARAM_STR);
            else $userIdQ->bindParam(':userid',$user_id,PDO::PARAM_INT);
            $userIdQ->execute();
            if ($userIdQ) {
                $res_user = $userIdQ->fetch(PDO::FETCH_ASSOC);
                $user_name = $res_user['name'];
                $user_id = $res_user['id'];
/*                echo '<pre style="direction: ltr;">';
                var_dump($user_id);
                var_dump($follower_id);
                echo '</pre>';*/
                $is_following = SocialWeb::is_following($follower_id,$user_id);
            }
        }

    }else{
        $user_name = $_SESSION['front_user_name'];
        $user_id = $_SESSION['front_user_id'];
    }
/*    if (isset($_POST['post'])) {
        $post_body = filter_input(INPUT_POST,'post',FILTER_SANITIZE_STRING);
        Login::query('INSERT INTO posts VALUES("",:post_body,NOW(),:user_id,0)', [':post_body' => $post_body, ':user_id' => $follower_id]);
    }*/
    $user_posts = SocialWeb::getPosts(1,$user_id);
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
            follow(<?= "'$follow_act'" ?>,<?= $user_id ?>).then((res)=>{
                if(res === 'true'){
                    $follow.val('הסר עוקב');
                }else if(res === 'false'){
                    $follow.val('עקוב');
                }else {
                    console.log(res);
                }

            });
		});

    </script>
<?php endif; ?>
<?php include 'app/components/publish_post.php'?>
<div id="postsFeed" style="background: #eeeeee">
    <?php if (isset($user_posts) && $user_posts): ?>
        <?php foreach ($user_posts as $post): ?>
            <?php include 'app/components/post_item_instance.php'; ?>
        <?php endforeach; ?>
    <?php else: ?>
    <div id="firstPostAlert">
        <h2>עדיין לא פרסמת פוסטים</h2>
        <p>נשמח לראות הפוסט הראשון שלך!</p>
    </div>
    <?php endif; ?>
</div>
<script>
	postPage = 1;
	function postsQuery() {
		return `api/index.php?action=get_posts&feed_page=${++postPage}&posted_by=<?= $user_id ?>`;
    }
    page = 'profile';
</script>
<?php else: ?>
<h1>המשתמש לא נמצא במערכת..</h1>
<?php endif; ?>



