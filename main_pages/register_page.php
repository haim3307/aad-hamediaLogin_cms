<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 20/01/2018
 * Time: 07:51
 */
function old($val){
    return isset($_REQUEST[$val])?$_REQUEST[$val]:'';
}

require_once '../class/Register.php';
session_start();
//$_SESSION['reg_level'] = 1;

$page_title = 'הרשמה';
$level = isset($_SESSION['reg_level']) ? $_SESSION['reg_level'] : 1;
if (isset($_POST['reg_level_1'])) {
    //var_dump($_POST);
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $user_email = filter_input(INPUT_POST, 'new_email', FILTER_VALIDATE_EMAIL);
    $user_pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $user_pass1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
    $user = ['user_name' => trim($user_name), 'password' => trim($user_pass), 'new_email' => trim($user_email), 'password1' => trim($user_pass1)];
    $err = Register::isEmpty($user);
    $errors = $err ? $err : [];
    if (!sizeof($errors)) {
        $insert = Register::insertRegStat($user_name, $user_email, $user_pass);
        if (!is_array($insert) && $insert) {
            $_SESSION['reg_level'] = 2;
            header('location:register_page.php');
        }
    }
}
if (isset($_POST['reg_level_2']) && isset($_SESSION['new_user_id'])) {
    if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] != 4){
        if ($profile_img_name = Register::checkImage('profile_img')) {
            if (move_uploaded_file($_FILES['profile_img']['tmp_name'], '../_img/users/profiles/' . $profile_img_name)) {
                $_SESSION['profile_img_name'] = $profile_img_name;

                $q = Register::connect()->query("UPDATE front_users SET profile_img='" . $profile_img_name . "' WHERE id=" . $_SESSION['new_user_id']);
                if ($q->rowCount() == 1) {
                    $_SESSION['reg_level'] = 3;
                    header('location:register_page.php');
                }
            }
        }
    }else{
        $_SESSION['reg_level'] = 3;
        header('location:register_page.php');
    }
}
if (isset($_POST['reg_level_3']) &&isset($_SESSION['new_user_id'])) {
    $optionals = ['first_name', 'last_name', 'birth_date', 'city', 'sex', 'desc'];
    $user_info = [];
    foreach ($optionals as $optional) {
        $user_info[$optional] = isset($_POST[$optional]) ? filter_input(INPUT_POST,$optional,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES) : '';
    }

    $profile_info = [
        ':uid' => $_SESSION['new_user_id'],
        ':first_name' => $user_info['first_name'],
        ':last_name' => $user_info['last_name'],
        ':birth_date' => $user_info['birth_date'],
        ':city' => $user_info['city'],
        ':sex' => $user_info['sex'],
        ':des' => $user_info['desc'],
    ];
    $if_exist = Register::connect()->query('SELECT uid FROM profiles_info WHERE uid=' . $_SESSION['new_user_id']);
    if (!$if_exist->fetch()) {
        $info_q = Register::query("INSERT INTO profiles_info 
        VALUES (:uid,:first_name,:last_name,:birth_date,:city,:sex,:des)", $profile_info);
        if ($info_q->rowCount()) {
            $_SESSION['reg_level'] = 4;
            header('location:register_page.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
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
                <li class="<?= $level === 1 ? 'active' : '' ?>">1</li>
                <li class="<?= $level === 2 ? 'active' : '' ?>">2</li>
                <li class="<?= $level === 3 ? 'active' : '' ?>">3</li>
                <li class="<?= $level === 4 ? 'active' : '' ?>">סיום</li>
            </ul>
        </div>
        <style>
            .userNameGroup {
                position: relative;
            }

            .hide {
                display: none;
            }
            .show{
                display: block;
            }
            .show-flex {
                display: flex !important;
            }

            .userNameGroup span {
                display: none;
                width: 70px;
                background: white;
                height: 78%;
                position: absolute;
                left: 5px;
                top: 10px;
                bottom: 0;
                margin: auto;
            }

            .userNameGroup input {
                width: 100%;
            }
        </style>
        <?php if ($level === 1): ?>
            <form action="" method="post" name="front_reg_form1" id="front_reg_form1"
                  enctype="application/x-www-form-urlencoded" novalidate>

                <section class="regLev m-auto-0" id="regLev1">
                    <h2 align="center">שלב א' פרטי כניסה</h2>
                    <?= isset($errors['user_name']['empty']) ? '<span class="alert-span">' . $errors['user_name']['empty'] . '</span>' : '' ?>
                    <div class="userNameGroup">
                        <span id="emptyA" class="alert-span hide <?= isset($errors['user_name']['empty'])?'show':'' ?>"> שדה זה אינו יכול להשאר ריק </span>
                        <input type="text" placeholder="שם משתמש" name="user_name" value="<?= old('user_name'); ?>">
                        <span id="used" class="all-centered" style="color: green;">זמין</span>
                        <span id="notUsed" class="all-centered" style="color: red">תפוס </span>
                        <span id="notValid" class="all-centered" style="color: red">אינו תקין</span>
                        <span id="empty" class="all-centered" style="color: red">ריק</span>
                    </div>
                    <?= isset($errors['user_name']['invalid']) ? '<span class="alert-span">' . $errors['user_name']['invalid'] . '</span>' : '' ?>
                    <span class="inputNote">חייב להתחיל באות , מינימום 5 תויים עד 32</span>
                    <?= isset($errors['new_email']['invalid']) ? '<span class="alert-span">' . $errors['new_email']['empty'] . '</span>' : ''; ?>
                    <div class="userNameGroup">
                        <span id="emptyA" class="alert-span hide <?= isset($errors['new_email']['empty'])?'show':'' ?>"> שדה זה אינו יכול להשאר ריק </span>
                        <input type="email" placeholder="אימייל" name="new_email" value="<?= old('new_email'); ?>">
                        <span id="used" class="all-centered" style="color: green;">זמין</span>
                        <span id="notUsed" class="all-centered" style="color: red">תפוס </span>
                        <span id="notValid" class="all-centered" style="color: red">אינו תקין</span>
                        <span id="empty" class="all-centered" style="color: red">ריק</span>
                    </div>
                    <?= isset($errors['new_email']['invalid']) ? '<span class="alert-span">' . $errors['new_email']['invalid'] . '</span>' : ''; ?>
                    <span class="inputNote">*אמייל זה ישמש גם לשחזור החשבון</span>
                    <?= isset($errors['password']['empty']) ? '<span class="alert-span">' . $errors['password']['empty'] . '</span>' : ''; ?>
                    <input type="password" placeholder="סיסמא" name="password">
                    <?= isset($errors['password']['invalid']) ? '<span class="alert-span">' . $errors['password']['invalid'] . '</span>' : ''; ?>
                    <span class="inputNote">*חייבת להכיל לפחות אות אחת גדולה,אותה קטנה,מספר אחד ומעל ל7 תווים</span>
                    <?= isset($errors['password1']['empty']) ? '<span class="alert-span">' . $errors['password1']['empty'] . '</span>' : ''; ?>
                    <input type="password" placeholder="חזור שנית על הסיסמא" name="password1">
                    <?= isset($errors['password1']['invalid']) ? '<span class="alert-span">' . $errors['password1']['invalid'] . '</span>' : ''; ?>
                    <input type="submit" name="reg_level_1">
                </section>
            </form>

        <?php elseif ($level === 2): ?>
            <form action="" method="post" name="front_reg_form2" id="front_reg_form2" enctype="multipart/form-data"
                  novalidate>

                <section class="regLev m-auto-0" id="regLev2">
                    <h2 align="center">שלב ב' העלה תמונת פרופיל</h2>
                    <?php if (isset($_SESSION['profile_img_name'])): ?>
                        <div class="profile_frame">
                            <img width="100%" src="../_img/users/profiles/<?= $_SESSION['profile_img_name'] ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <div class="ft g-col-12 g-col-lg-6 profileImgSec" style="">
                        <div class="g-col-12">
                            <div class="form-group inputDnD" style="height: 416px;">
                                <label class="sr-only" for="inputFile">File Upload</label>
                                <input type="file" name="profile_img"
                                       class="form-control-file text-primary font-weight-bold" id="inputFile"
                                       accept="image/*" onchange="readUrl(this)" data-title="גרור את התמונה או לחץ כאן">

                            </div>
                        </div>

                    </div>
                    <input type="submit" name="reg_level_2">
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
                </section>
            </form>

        <?php elseif ($level === 3): ?>

            <form action="" method="post" name="front_reg_form3" enctype="application/x-www-form-urlencoded" novalidate>

                <section class="regLev m-auto-0" id="regLev3">
                    <h2 align="center">שלב ג' פרטי פרופיל</h2>
                    <input type="text" name="first_name" placeholder="שם פרטי" value="<?= old('first_name')?>">
                    <input type="text" name="last_name" placeholder="שם משפחה" value="<?= old('last_name') ?>">
                    <input type="date" name="birth_date" placeholder="תאריך לידה" value="<?= old('birth_date') ?>">
                    <input type="text" name="city" placeholder="עיר מגורים" value="<?= old('city') ?>">
                    <?php $select_sex_val = old('sex')?>
                    <select name="sex" id="sex"">
                        <option value="male" <?=$select_sex_val == 'null' ? ' selected="selected"' : '';?>>בחר מין</option>
                        <option value="male" <?=$select_sex_val == 'male' ? ' selected="selected"' : '';?>>זכר</option>
                        <option value="female" <?=$select_sex_val == 'female' ? ' selected="selected"' : '';?>>נקבה</option>
                        <option value="unset" <?=$select_sex_val == 'unset' ? ' selected="selected"' : '';?>>לא מוגדר</option>
                    </select>
                    <textarea name="desc" id="desc" placeholder="ספר קצת על עצמך" cols="30" rows="10"><?= old('description') ?></textarea>
                    <input type="submit" name="reg_level_3">
                </section>

            </form>
        <?php elseif ($level === 4): ?>
        <h1> <?= $_SESSION['new_user_name'] ?>  נרשמת בהצלחה! </h1>
        <h2> כעת באפשרותך להתחבר! </h2>
        <?php endif; ?>

    </main>
    <footer><?php include_once '../main_layout/footer.php' ?></footer>
</div>
<script>

	(function () {
		let reg_preg = {
			'user_name': /^[A-Za-z][A-Za-z0-9]{5,31}$/,
			'new_email': /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i,
			'password': /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/,
		};
		let prev_user_name = '';
		let submit1 = true;

		function isUsed(param) {
			$('input[name="' + param + '"]').on('blur', function (e) {
				let current_value = e.target.value;
				if(!current_value) $(e.target).siblings('#empty').addClass('show-flex');
				else $(e.target).siblings('#empty').removeClass('show-flex');
				if (prev_user_name === current_value) return;
				if (reg_preg[param].test(current_value)) {
					$.ajax({
						method: 'POST',
						url: '../class/Register.php',
						data: {
							act: 'check_' + param,
							['check_' + param]: current_value
						}
					}).done(function (res) {
						console.log(res);
						if (res === 'not used') {
							$(e.target).siblings('#used').addClass('show-flex');
							$(e.target).siblings('#notUsed').removeClass('show-flex');
							submit1 = true;
						} else {

							$(e.target).siblings('#used').removeClass('show-flex');
							$(e.target).siblings('#notUsed').addClass('show-flex');
							submit1 = false;

						}
					});
					$(e.target).siblings('#notValid').removeClass('show-flex');
				} else {
					$(e.target).siblings('#notValid').addClass('show-flex');
				}
				prev_value = current_value;

			});
		}

		isUsed('user_name');
		isUsed('new_email');

		$('#front_reg_form1').on('submit', function () {
			//$('input[name="user_name"]')
			return submit1;
		});
	})();

</script>
</body>
</html>
