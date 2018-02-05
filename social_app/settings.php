<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
if(isset($_POST['update_new_profile_img'])){
    require_once 'class/Register.php';
    if($profile_img_name = Register::check_image('new_profile_img')){
        if( move_uploaded_file($_FILES['new_profile_img']['tmp_name'],'_img/users/profiles/'.$profile_img_name)){

            $q = Register::connect()->query("UPDATE front_users SET profile_img='".$profile_img_name."' WHERE id=".$_SESSION['front_user_id']);
            if($q->rowCount() == 1){
                $_SESSION['profile_img_name'] = $profile_img_name;
            }
        }
    }
}
?>
<h1>הגדרות</h1>
<form action="" method="post" enctype="multipart/form-data">
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
</form>
<script>let postPage = 'settings';</script>
