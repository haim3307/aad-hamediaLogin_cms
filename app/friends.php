<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 14:36
 */
if($q1 = $con->query("SELECT u.name,u.id,u.profile_img FROM followers f JOIN front_users u ON u.id = f.uid WHERE f.fid=$is_logged AND u.id != {$_SESSION['front_user_id']}")){
    $following_list = $q1->fetchAll(PDO::FETCH_ASSOC);
}
if($q2 = $con->query("SELECT u.name,u.id,u.profile_img FROM followers f JOIN front_users u ON u.id = f.fid WHERE f.uid=$is_logged AND u.id != {$_SESSION['front_user_id']}")){
    $followed_by_list = $q2->fetchAll(PDO::FETCH_ASSOC);
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
                $row['followed_by_you'] = Social_web::is_following($_SESSION['front_user_id'],$row['id'])?1:0;
                $members_results[] = $row;
            }
        }
    }
}
?>
<style>
    .friendsPage{

    }
    .followLists{
        display: grid;
    }

    .user_item{
        display: grid;
        grid-template-columns: 1fr 3fr;
    }

    .searchMembers{
        margin: 10px 0;
    }
    .searchMembersResults{
        padding: 5px 20px;
        display: grid;
        grid-gap: 10px;
    }
    .searchGroup{
        display: flex;
        margin: 0 auto;
        max-width: 500px;
    }
    @media(min-width: 810px) {
        .g-col-3{
            grid-column: span 3;
        }
        .searchMembersResults {
            grid-template-columns: repeat(3,1fr);
        }
    }
    @media(min-width: 410px) {

        .followLists{
            grid-template-columns: 1fr 1fr;
        }

    }
</style>
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
                    <div>
                        <h1>
                            אתה עוקב אחרי
                        </h1>
                        <?php if(isset($following_list) && count($following_list)):?>
                            <div>
                                <?php foreach ($following_list as $following):?>
                                    <user-item
                                            user-id="<?= $following['id']; ?>"
                                            user-name="<?= $following['name']; ?>"
                                            user-profile-img="<?= $following['profile_img']; ?>"
                                            followed-by-you="1"
                                    ></user-item>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <h4>עדיין לא עקבת אחריי אף אחד</h4>
                        <?php endif; ?>

                    </div>

                    <div>
                        <h1>עוקבים אחרייך</h1>
                        <?php if(isset($followed_by_list) && count($followed_by_list)):?>
                            <ul>
                                <?php foreach ($followed_by_list as $followed_by):?>
                                    <li>
                                        <a href="index.php?app-page=profile&username=<?= $followed_by['name']; ?>" class="user_item">
                                            <span class="profileFrame all-centered"><img src="_img/users/profiles/<?= $followed_by['profile_img']; ?>" alt=""></span>
                                            <span><?= $followed_by['name']; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
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
<script>
	class UserItem extends HTMLElement {
		constructor() {
			super();
			this.user = {
				id: this.getAttribute('user-id'),
				name: this.getAttribute('user-name'),
				profile_img: this.getAttribute('user-profile-img')
			};
			this.followStatus = this.getAttribute('followed-by-you');
			this.searchMode = this.getAttribute('search-mode');
			this.className = 'user_item';
			this.followedByYou = this.followStatus == '1';

		}
		followBtnTpl(){
			return `<input type="button" id="remove_follow" value="${!this.followedByYou?'עקוב':'הסר עוקב'}">`;
		}
		connectedCallback() {
			this.innerHTML =
				`
                    <span class="profileFrame all-centered"><img src="_img/users/profiles/${this.user.profile_img}" alt=""></span>
                    <div data-following-id="${this.user.id}">
                        <span><a href="index.php?app-page=profile&username=${this.user.name}">${this.user.name}</a></span><br>
                        <span class="followSpan">${this.followBtnTpl()}</span>
                    </div>
                `;
			const _class = this,$user = $(this),$follow = $user.find('.followSpan');
			let fc = !this.searchMode ? followClick:searchModeClick;
			function followClick() {
				follow('false',_class.user.id).then((res)=>{
					if(res === 'false'){
						$user.remove();
					}

				});
			}
			function searchModeClick () {
				follow(!_class.followedByYou,_class.user.id).then((res)=>{
					console.log(res);
					_class.followedByYou = res == 'true';
					$user.find('.followSpan').html(_class.followBtnTpl());

				});
			}
			$follow.on('click', fc);

		}
	}

	window.customElements.define('user-item', UserItem);
</script>