<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 14:36
 */
if($q1 = $con->query("SELECT u.name,u.id,u.profile_img FROM followers f JOIN front_users u ON u.id = f.uid WHERE f.fid=$is_logged AND u.id != {$_SESSION['front_user_id']}")){
    while ($row = $q1->fetch(PDO::FETCH_ASSOC)){
        $row['followed_by_you'] = SocialWeb::is_following($_SESSION['front_user_id'],$row['id'])?1:0;
        $following_list[] = $row;
    }
}
if($q2 = $con->query("SELECT u.name,u.id,u.profile_img FROM followers f JOIN front_users u ON u.id = f.fid WHERE f.uid=$is_logged AND u.id != {$_SESSION['front_user_id']}")){
    while ($row = $q2->fetch(PDO::FETCH_ASSOC)){
        $row['followed_by_you'] = SocialWeb::is_following($_SESSION['front_user_id'],$row['id'])?1:0;
        $followed_by_list[] = $row;
    }
}
if(isset($_GET['search_member'])){
    $member_name = filter_input(INPUT_GET,'member_name',FILTER_SANITIZE_STRING);
    $member_name = trim($member_name);
    if($member_name){
        $members_results_q = $con->query(
                "SELECT fu.id,fu.name,fu.profile_img FROM front_users fu WHERE fu.name LIKE '$member_name%' AND fu.id != {$_SESSION['front_user_id']}"
        );
        if($members_results_q){
            while ($row = $members_results_q->fetch(PDO::FETCH_ASSOC)){
                $row['followed_by_you'] = SocialWeb::is_following($_SESSION['front_user_id'],$row['id'])?1:0;
                $members_results[] = $row;
            }
        }
    }
}
?>
<div class="friendsPage">
    <div class="allMemebers">
        <section class="searchMembers">
            <form action="index.php">
                <input type="hidden" name="app-page" value="friends">
                <div class="searchGroup">
                    <input type="text" name="member_name" style="flex: 1;" value="<?= old('member_name') ?>" placeholder="חפש חברים ברשת..">
                    <label for="search_member" style="background: linear-gradient(to bottom, #a90329 0%, #8f0222 44%, #6d0019 100%);
                         padding: 14px 20px; color: white">
                        <i class="fa fa-search"></i>
                        <input type="submit" id="search_member" name="search_member" style="display: none;">
                    </label>
                </div>
                <?php if(isset($_GET['search_member']) && trim($_GET['member_name'])):?>
                <div class="searchMembersResults">
                    <?php if(isset($members_results) && count($members_results)):?>
                        <h3 style="text-align: center; padding: 10px;" class="g-col-3"> מציג <strong><?= count($members_results) ?></strong> תוצאות עבור <strong>"<?= htmlentities($_GET['member_name']) ?>"</strong> </h3>

                        <?php foreach ($members_results as $member):?>
                            <user-item
                                    user-id="<?= $member['id']; ?>"
                                    user-name="<?= $member['name']; ?>"
                                    user-profile-img="<?= $member['profile_img']; ?>"
                                    followed-by-you="<?= $member['followed_by_you'] ?>"
                                    search-mode="1"
                            ></user-item>
                        <?php endforeach; ?>
                    <?php elseif(isset($_GET['search_member']) && trim($_GET['member_name'])): ?>
                        <h4 style="text-align: center;" class="g-col-3">לא נמצאו תוצאות</h4>

                    <?php endif; ?>
                </div>
                <?php else:?>

                <div class="followLists">
                    <div class="user_items_list">
                        <h2 class="list_title">
                            אתה עוקב אחרי
                        </h2>
                        <?php if(isset($following_list) && count($following_list)):?>
                            <?php foreach ($following_list as $following):?>
                                <user-item
                                        user-id="<?= $following['id']; ?>"
                                        user-name="<?= $following['name']; ?>"
                                        user-profile-img="<?= $following['profile_img']; ?>"
                                        followed-by-you="1"
                                ></user-item>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h4>עדיין לא עקבת אחריי אף אחד</h4>
                        <?php endif; ?>

                    </div>

                    <div class="user_items_list">
                        <h2 class="list_title">עוקבים אחרייך</h2>
                        <?php if(isset($followed_by_list) && count($followed_by_list)):?>
                            <?php foreach ($followed_by_list as $followed_by):?>
                                <user-item
                                        user-id="<?= $followed_by['id']; ?>"
                                        user-name="<?= $followed_by['name']; ?>"
                                        user-profile-img="<?= $followed_by['profile_img']; ?>"
                                        followed-by-you="<?= $followed_by['followed_by_you'] ?>"
                                        search-mode="1"
                                ></user-item>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h4>אין לך עוקבים</h4>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endif; ?>
            </form>

        </section>
        <section class="newMembers">

        </section>
    </div>
</div>
<script src="app/components/user-item.js"></script>