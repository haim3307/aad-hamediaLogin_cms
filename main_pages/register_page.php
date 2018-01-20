<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 20/01/2018
 * Time: 07:51
 */


require_once '../class/Register.php';
session_start();
$_SESSION['reg_level'] = 1;

$page_title = 'הרשמה';
$level= isset($_SESSION['reg_level'])?$_SESSION['reg_level']:1;
if(isset($_POST['reg_level_1'])){
    //var_dump($_POST);
    $err = Register::is_empty($_POST);
    $errors = $err?$err:[];

    $_SESSION['reg_level'] = 2;
}
if(isset($_POST['reg_level_2'])){
    $_SESSION['reg_level'] = 3;
}
?>
<!DOCTYPE html>
<html>
<style>
    .menuBanner .menuBannerImg {
        height: 100%;
        width: 100%;
        max-height: 183px;
    }
</style>
<head>
    <title><?= $page_title ?> - עד המדינה!</title>
    <?php include_once '../main_layout/head.php' ?>
</head>

<body>

<div class="flex-container">
    <header>
        <?php include_once '../main_layout/header.php' ?>

    </header>
    <main class="main">
        <div class="title"><a href="../index.php" class="title"> <?= $page_title ?></a></div>
        <div>
            <ul class="regLevelsBar all-centered-lis m-auto-0">
                <li class="<?= $level === 1?'active':''?>">1</li>
                <li class="<?= $level === 2?'active':''?>">2</li>
                <li class="<?= $level === 3?'active':''?>">3</li>
            </ul>
        </div>
        <form action="" method="post" name="front_reg_form" class="front_reg_form" novalidate>
            <?php if($level === 1):?>
                <section class="regLev m-auto-0" id="regLev1">
                    <h2 align="center">שלב א' פרטי כניסה</h2>
                    <?= isset($errors['user_name']['empty'])?'<span class="alert-span">'.$errors['user_name']['empty'].'</span>':'' ?>
                    <input type="text" placeholder="שם משתמש" name="user_name">
                    <?= isset($errors['user_name']['invalid'])?'<span class="alert-span">'.$errors['user_name']['invalid'].'</span>':'' ?>
                    <span class="inputNote">*חייב להכיל לפחות אות אחד גדולה ואחת קטנה</span>
                    <?= isset($errors['new_email']['empty'])?'<span class="alert-span">'.$errors['new_email']['empty'].'</span>':''; ?>
                    <input type="email" placeholder="אימייל" name="new_email">
                    <?= isset($errors['new_email']['invalid'])?'<span class="alert-span">'.$errors['new_email']['invalid'].'</span>':''; ?>
                    <span class="inputNote">*אמייל זה ישמש גם לשחזור החשבון</span>
                    <?= isset($errors['password']['empty'])?'<span class="alert-span">'.$errors['password']['empty'].'</span>':''; ?>
                    <input type="password" placeholder="סיסמא" name="password">
                    <?= isset($errors['password']['invalid'])?'<span class="alert-span">'.$errors['password']['invalid'].'</span>':''; ?>
                    <span class="inputNote">*חייב להכיל לפחות אות אחד גדולה ומעל ל7 תווים</span>
                    <?= isset($errors['password1']['empty'])?'<span class="alert-span">'.$errors['password1']['empty'].'</span>':''; ?>
                    <input type="password" placeholder="חזור שנית על הסיסמא" name="password1">
                    <?= isset($errors['password1']['invalid'])?'<span class="alert-span">'.$errors['password1']['invalid'].'</span>':''; ?>
                    <input type="submit" name="reg_level_1">
                </section>
            <?php elseif($level === 2):?>
                <section class="regLev m-auto-0" id="regLev2">
                    <h2 align="center">שלב ב' העלה תמונת פרופיל</h2>
                    <div class="ft g-col-12 g-col-lg-6 profileImgSec" style="">
<!--                        <h3 class="g-col-12 g-col-lg-6">4. העלה תמונת פרופיל</h3>
-->                        <div class="g-col-12">
                            <div class="form-group inputDnD" style="height: 416px;">
                                <label class="sr-only" for="inputFile">File Upload</label>
                                <input type="file"  name="profile_img" class="form-control-file text-primary font-weight-bold" id="inputFile" accept="image/*" onchange="readUrl(this)" data-title="גרור את התמונה או לחץ כאן">
                            </div>
                        </div>

                    </div>
                    <input type="submit" name="reg_level_2">
                    <script>
                        function readUrl(input) {

                            if (input.files && input.files[0]) {
                                let reader = new FileReader();
                                reader.onload = (e) => {
                                    let imgData = e.target.result;
                                    let imgName = input.files[0].name;
                                    input.setAttribute("data-title", imgName);
                                    console.log(e.target.result);
                                };
                                reader.readAsDataURL(input.files[0]);
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
                            -webkit-box-shadow: inset 0px 0px 0px 0.25em currentColor;
                            box-shadow: inset 0px 0px 0px 0.25em currentColor;
                        }
                        .warningsInput{
                            min-height: 50px;
                            border-radius: 6px;
                        }
                        /*!custom file*/
                    </style>
                </section>
            <?php elseif($level === 3):?>
                <section class="regLev m-auto-0" id="regLev3">
                    <h2 align="center">שלב ג' פרטי פרופיל</h2>
                    <input type="text" placeholder="שם פרטי">
                    <input type="text" placeholder="שם משפחה">
                    <input type="number" placeholder="גיל">
                    <input type="text" placeholder="עיר מגורים">
                    <select name="" id="">
                        <option value="">בחר מין</option>
                        <option value="male">זכר</option>
                        <option value="female">נקבה</option>
                        <option value="unset">לא מוגדר</option>
                    </select>
                    <textarea name="" id="" placeholder="ספר קצת על עצמך" cols="30" rows="10">

                    </textarea>
                    <input type="submit" name="reg_level_3">
                </section>
            <?php endif; ?>
        </form>
    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>
<script>
  (function () {
		let reg_preg = {
			'user_name':/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/,
			'new_email':/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i,
			'password':/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/,
		};
		let prev_user_name = '';
        function isUsed(param) {
            $('input[name="'+param+'"]').on('blur',function (e) {
                let current_value = e.target.value;
                if(prev_user_name === current_value) return;
                if(reg_preg[param].test(current_value)){
                    $.ajax({
                        method:'POST',
                        url:'../class/Register.php',
                        data: {
                        	act:'check_'+param,
                        	['check_'+param]:current_value
                        }
                    }).done(function (res) {
                      console.log(res);
					});
                }
                prev_value = current_value;

            });
		}
        isUsed('user_name');
        isUsed('new_email');
  })();
</script>
</body>
</html>
