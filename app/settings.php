<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
if(isset($_POST['update_new_profile_img'])){
    require_once 'class/Register.php';
    if($profile_img_name = Register::checkImage('new_profile_img')){
        if( move_uploaded_file($_FILES['new_profile_img']['tmp_name'],'_img/users/profiles/'.$profile_img_name)){

            $q = Register::connect()->query("UPDATE front_users SET profile_img='".$profile_img_name."' WHERE id=".$_SESSION['front_user_id']);
            if($q->rowCount() == 1){
                $_SESSION['profile_img_name'] = $profile_img_name;
            }
        }
    }
}

$expected_info = ['first_name','last_name','birth_date','city','sex','description'];
if(isset($_POST['save_profile_info'])){
    $args = [];
    $query = 'UPDATE profiles_info SET ';
    $first_cell = true;
    foreach ($_POST as $key=>$val){
        if(in_array($key,$expected_info)){
            if($val = filter_input(INPUT_POST,$key,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES)){
                if($val = trim($val)){
                    $args[':'.$key] = $val;
                    if($first_cell) {
                        $query.= "$key = :$key";
                        $first_cell = false;
                    }else $query.= ", $key = :$key";
                }
            }
        }
    }
    if($args){
        $args[':uid'] = $_SESSION['front_user_id'];
        $profile_info_q = Connection::query($query.' WHERE uid = :uid', $args);
    }
}
$profile_info_q = Connection::query('SELECT * FROM profiles_info WHERE uid = :uid', [':uid'=>$_SESSION['front_user_id']]);
if($profile_info_q){
    $profile_info = $profile_info_q->fetch(PDO::FETCH_ASSOC);
    foreach ($profile_info as $key => $val){
        $_REQUEST[$key] = $val;
    }
}
?>
<style>
    .settingsPage{
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-areas:
                'updateProfileImg updateProfileImg'
                'updateUserInfo updateUserInfo'
                'switchPass switchPass'
        ;
    }


    .updateProfileImg{
        grid-area: updateProfileImg;
    }
    .switchPass{
        grid-area: switchPass;
        padding: 10px;
    }
    .updateUserInfo{
        grid-area: updateUserInfo;
        padding: 10px;
    }
    .settingsPage input{
        border-radius: 5px;
        padding: 5px 10px;
    }
    .settingsPage input[type="button"], .settingsPage input[type="submit"]{
        padding: 5px 10px;
        margin: 10px 0;
        background-color: #8f0222;
        color: #ffffff;
    }
    .g-col-2{
        grid-column: span 2;
    }
</style>
<div class="settingsPage">
    <form class="updateProfileImg" style="grid-column: span 2" action="" method="post" enctype="multipart/form-data">
        <h2>עדכן תמונת פרופיל</h2>
        <div class="ft g-col-12 g-col-lg-6 profileImgSec" style="">
            <div class="g-col-12" style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="form-group inputDnD" style="height: 416px; padding: 0 10px">
                    <label class="sr-only" for="inputFile">File Upload</label>
                    <input type="file" name="new_profile_img"
                           class="form-control-file text-primary font-weight-bold" id="inputFile"
                           accept="image/*" onchange="readUrl(this)" data-title="גרור את התמונה או לחץ כאן">

                </div>
                <?php if(isset($_SESSION['front_profile_img'])): ?>
                    <div class="profile_frame">
                        <img width="100%" src="_img/users/profiles/<?= $_SESSION['front_profile_img'] ?>" alt="">
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <input type="submit" name="update_new_profile_img" value="עדכן">
        <script>
					function readUrl(input) {

						if (input.files && input.files[0]) {
							input.setAttribute("data-title", input.files[0].name);
						}

					}
        </script>
        <style>
            /*custom file*/
            .inputDnD .form-control-file {
                position: relative;
                width: 100%;
                height: 100%;
                min-height: 6em;
                outline: none;
                visibility: hidden;
                cursor: pointer;
                background-color: #c61c23;
                -webkit-box-shadow: 0 0 5px red;
                box-shadow: 0 0 5px red;
            }

            .inputDnD .form-control-file:before {
                content: attr(data-title);
                position: absolute;
                top: 0.5em;
                left: 0;
                width: 100%;
                height: 380px;
                line-height: 2em;
                padding-top: 1.5em;
                opacity: 1;
                visibility: visible;
                text-align: center;
                border: 0.25em dashed currentColor;
                -webkit-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                overflow: hidden;
            }

            .inputDnD .form-control-file:hover:before {
                border-style: solid;
                -webkit-box-shadow: inset 0 0 0 0.25em currentColor;
                box-shadow: inset 0 0 0 0.25em currentColor;
            }

            .warningsInput {
                min-height: 50px;
                border-radius: 6px;
            }

            /*!custom file*/
        </style>
        <style>
            .inputDnD .form-control-file:before{
            }
            .profile_frame{
                padding: 30px;
            }
        </style>
        <hr class="g-col-2">
    </form>
    <div class="switchPass">
        <h2 class="g-col-3">החלף סיסמה</h2>
        <a href="<?= DOMAIN.'settings/changing-password.php' ?>"><input type="button" value="לדף ההחלפה"></a>
    </div>
    <form action="" class="updateUserInfo" method="post" style="display: grid; grid-template-columns: repeat(3,1fr); grid-gap: 30px; max-width: 600px;">
        <h2 class="g-col-3">פרטים אישים</h2>
        <div class="inputGroup">
            <label for="">שם פרטי:</label>
            <input type="text" name="first_name" value="<?= old('first_name') ?>">
        </div>
        <div class="inputGroup">
            <label for="">שם משפחה:</label>
            <input type="text" name="last_name" value="<?= old('last_name') ?>">
        </div>
        <div class="inputGroup">
            <label for="">תאריך לידה:</label>
            <input type="date" name="birth_date" value="<?= explode(' ',old('birth_date'))[0] //date('dd/MM/yyyy',) ?>">
        </div>
        <div class="inputGroup">
            <label for="">עיר מגורים:</label>
            <input type="text" name="city" value="<?= old('city') ?>">
        </div>
        <div class="inputGroup" style="display: grid;">
            <label for="">מין:</label>
            <?php $select_sex_val = old('sex')?>
            <select name="sex" id="">
                <option value="male" <?=$select_sex_val == 'male' ? ' selected="selected"' : '';?>>זכר</option>
                <option value="female" <?=$select_sex_val == 'female' ? ' selected="selected"' : '';?>>נקבה</option>
                <option value="unset" <?=$select_sex_val == 'unset' ? ' selected="selected"' : '';?>>לא מוגדר</option>
            </select>
        </div>
        <div class="inputGroup" style="grid-column: span 3; display: grid;">
            <label for="">קצת על עצמך:</label>
            <textarea name="description" id="" rows="10"><?= old('description') ?></textarea>
        </div>
        <input type="submit" name="save_profile_info" value="שמור שינויים">
    </form>
</div>

<script>let postPage = 'settings';</script>
