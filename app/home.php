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
            $posts = Social_web::get_posts(1);
            ?>
            <?php foreach ($posts as $post): ?>
                <?php include 'app/components/post_item_instance.php'; ?>
            <?php endforeach; ?>
            <?php if(!$is_logged): ?>
                <h3 style="padding-bottom: 20px;">עלייך <a href="#">להתחבר</a> על מנת לראות פוסטים נוספים</h3>
            <?php endif; ?>
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
