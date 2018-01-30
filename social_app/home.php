<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 12:11
 */
?>
<?php if(isset($_SESSION['front_user_id'])): ?>
    <section class="uploadPost">
        <style>
            .uploadPost{
                background-color: #fff;
            }
            .uploadPost .publishPost input[type=button]{
                width: 20%;
                height: 40px;
            }
            .ltr{
                direction: ltr;
            }
            .row{
                display: flex;
            }
            .post textarea{
                flex: 1;
                margin-top: 30px;
                border-radius: 3%;
                border: 0;
            }

            .publishPost input[type="button"]{
                background: linear-gradient(to bottom, #a90329 0%, #8f0222 44%, #6d0019 100%);
                color: white;
                border-radius: 10px;
            }
            #postsFeed{
                width: 100%;
                margin: 0 auto; max-width: 900px;
            }
        </style>
        <div class="row post">
            <div class="profileFrame all-centered">
                <?php if(isset($_SESSION['front_profile_img']) && !empty($_SESSION['front_profile_img'])): ?>
                    <img src="_img/users/profiles/<?= $_SESSION['front_profile_img']; ?>" alt="<?= $_SESSION['front_user_name']; ?>">
                <?php else: ?>
                    <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                <?php endif; ?>
            </div>
            <textarea name="" id="postContent" placeholder="על מה אתה חושב היום?" rows="8"></textarea>
        </div>
        <div class="ltr publishPost" style="display: flex; align-items: center; ">
            <input id="publishPostI" type="button" value="פרסם">
            <div style="flex: 1; direction: rtl; padding: 0 50px; display: grid; grid-column-gap: 1em; grid-template-columns: repeat(5,1fr)">
                <label for="post_image" style="text-align: center;"><i class="fa fa-image" title="העלה תמונה"></i></label>
                <input type="file" id="post_image" style="display: none;">
            </div>
        </div>
    </section>
<?php endif; ?>
<div class="allReportsWrapperBig">
    <!--            <div class="allReportsWrapperL"></div>
                <div class="allReportsWrapperR"></div>-->
    <div class="allReportsWrapper">
        <div class="news-home" id="postsFeed">


            <?php
            $posts = Social_web::get_posts(0);
            ?>
            <?php foreach ($posts as $post): ?>
                <post-item
                    post-id="<?= $post['id'] ?>"
                    main-user-id="<?= isset($_SESSION['front_user_id'])?$_SESSION['front_user_id']:'undefined' ?>"
                    title="<?= $post['title']; ?>"
                    author="<?= $post['author']; ?>"
                    added-date="<?= $post['added_date']; ?>"
                    user-id="<?= $post['uid']; ?>"
                    profile-img="<?= $post['profile_img']; ?>"
                    front-img="<?= $post['front_img']; ?>"
                    post-show="true"
                ></post-item>
            <?php endforeach; ?>
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
