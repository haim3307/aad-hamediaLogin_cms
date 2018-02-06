<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 31/01/2018
 * Time: 14:27
 */
/*;*/
?>
<post-item
    post-id="<?= $post['id'] ?>"
    main-user-id="<?= isset($_SESSION['front_user_id'])?$_SESSION['front_user_id']:'undefined' ?>"
    title="<?= $post['title']; ?>"
    author="<?= $post['name']; ?>"
    added-date="<?= $post['added_date']; ?>"
    user-id="<?= $post['uid']; ?>"
    profile-img="<?= $post['profile_img']; ?>"
    front-img="<?= $post['front_img']; ?>"
    post-show="true"
    posted-liked="<?= $post['liked']; ?>"
    likers-list = '<?= $post['likers_list']; ?>'
    likes-count = '<?= $post['likes_count']; ?>'
    <?php if($app_page === 'profile'): ?>
    posted-to="<?= $post['to'] ?>"
    posted-to-name="<?= $post['_to'] ?>"
    show-posted-to = "true"
    <?php endif; ?>
></post-item>
<?php
/*if($app_page === 'profile'){
echo '<pre style="direction: ltr;">';
var_dump($post['to']);
var_dump($post['uid']);
var_dump($app_page);
echo '</pre>';
}*/
?>

