<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 12:11
 */
?>
<?php if($is_logged): ?>
    <?php include 'app/components/publish_post.php'?>
<?php endif; ?>
<div class="allReportsWrapperBig">
    <!--            <div class="allReportsWrapperL"></div>
                <div class="allReportsWrapperR"></div>-->
    <div class="allReportsWrapper" style="background: #eeeeee">
        <div class="news-home" id="postsFeed">
            <?php
            if($is_logged){
                $posts = SocialWeb::getPosts(1);
                foreach ($posts as $post){
                    include 'app/components/post_item_instance.php';
                }
            }else{
                echo '<h3 style="padding-bottom: 20px;">עלייך <a href="#">להתחבר</a> על מנת לראות פוסטים </h3>';
            }
            ?>
        </div>

    </div>
</div>
<script>
	postPage = 1;
	function postsQuery() {
        return `api/index.php?action=get_posts&feed_page=${++postPage}`;
	}
    page = 'home';
</script>
