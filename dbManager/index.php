<?php
if (!isset($_GET['act'])) {
    //echo 'yes';
    if (isset($_POST['user_remember'])) session_set_cookie_params(60 * 60 * 24 * 7);
}
session_start();
if (!isset($_SESSION['loggedIN']) || $_SESSION['loggedIN'] !== 1) {
    header('location: start.php');
}

define('MAX_FILE_SIZE', 1024 * 1024 * 5);
//echo 'true';
if (isset($_POST['register'])) {
    echo 'true1';
    if (validateMust()) {
        echo 'true2';

        require_once '../class/register.php';
        $regRequest = new Register();
        $id = $regRequest->insertReg($_POST['user_name'], $_POST['email'], $_POST['user_pass']);

    }


}


function validateMust()
{
    $phoneRegExp = "/^0[2-9]\d{7,8}$/";
    $emailRegExp = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
    $passRegExp = "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";
    //if(!empty($_POST['age']) && is_numeric($_POST['age']) && strlen($_POST['age']) <= 4)
    echo '0.start';
    if (!empty($_POST['optionals']['private_phone']) && preg_match($phoneRegExp, $_POST['optionals']['private_phone'])) {
        echo '1.phone good';
        if (!empty($_POST['email']) && preg_match($emailRegExp, $_POST['email'])) {
            echo '2.email good';
            $nameLen = strlen($_POST['user_name']);
            if (!empty($_POST['user_name']) && $nameLen >= 5 && $nameLen < 50) {
                echo '3.uname good';
                if (!empty($_POST['user_pass']) && preg_match($passRegExp, $_POST['user_pass'])) {
                    echo '4.pass good';
                    if (!empty($_POST['user_sec_pass']) && $_POST['user_sec_pass'] == $_POST['user_pass']) {
                        echo '5.spass good';
                        return true;
                    }
                }
            }
        }
    }
}

$mainCDN = 'https://cdnjs.cloudflare.com/ajax/libs/';
$secondCDN = 'https://maxcdn.bootstrapcdn.com/';
$localScripts = 'lib/';
$localLinks = 'css/';
?>

<!doctype html>
<html lang="en" ng-app="admin">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>פאנל אדמין</title>
    <?php if($_SERVER['HTTP_HOST'] !== 'localhost'): ?>
        <script src="<?= $mainCDN ?>angular.js/1.6.8/angular.min.js"></script>
        <script src="<?= $mainCDN ?>angular-ui-router/1.0.3/angular-ui-router.js"></script>
        <script src="<?= $localScripts ?>ng-file-model.js"> </script>

        <script src="<?= $mainCDN ?>jquery/3.2.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script src="<?= $mainCDN ?>popper.js/1.12.3/umd/popper.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $secondCDN ?>bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" crossorigin="anonymous">
        <script src="<?= $secondCDN ?>bootstrap/4.0.0-beta.2/js/bootstrap.min.js" crossorigin="anonymous"></script>


        <script src="<?= $mainCDN ?>toastr.js/latest/js/toastr.min.js"></script>
        <link rel="stylesheet" href="<?= $mainCDN ?>toastr.js/latest/toastr.min.css">

        <link href="<?= $mainCDN ?>summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $mainCDN ?>summernote/0.8.9/font/summernote.ttf">
        <link rel="stylesheet" href="<?= $mainCDN ?>summernote/0.8.9/font/summernote.woff">
        <script src="<?= $mainCDN ?>summernote/0.8.9/summernote-bs4.min.js"></script>

        <script src="<?= $localScripts ?>moment.js"></script>


        <link rel="stylesheet" href="<?= $secondCDN ?>font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?= $mainCDN ?>font-awesome/4.7.0/fonts/fontawesome-webfont.woff2">
        <link rel="stylesheet" href="css/font-awesome.css">


    <!--DEV SCRIPTS-->
    <?php
    else:
        $scripts = [
            'http_cdnjs.cloudflare.com_ajax_libs_angular.js_1.6.8_angular.js',
            'http_cdnjs.cloudflare.com_ajax_libs_angular-ui-router_1.0.3_angular-ui-router.js',
            'ng-file-model.js',
            'moment.js',
            'jquery-3.2.1.js',
            'http_code.jquery.com_ui_1.12.1_jquery-ui.js',
            'http_cdnjs.cloudflare.com_ajax_libs_popper.js_1.12.3_umd_popper.js',
            'http_maxcdn.bootstrapcdn.com_bootstrap_4.0.0-beta.2_js_bootstrap.js',
            'http_cdnjs.cloudflare.com_ajax_libs_summernote_0.8.8_summernote-bs4.js',
            'http_cdnjs.cloudflare.com_ajax_libs_toastr.js_latest_js_toastr.min.js',
        ];
        $links = [
            'bootstrap.min.css',
            'font-awesome.css',
            'summernote-bs4.css',
        ];
        $scripts_html = '';
        $links_html = '';
        foreach ($scripts as $script) $scripts_html .= '<script src="'.$localScripts.$script.'"></script>';

        foreach ($links as $link) $links_html .= '<link rel="stylesheet" href="'.$localLinks.$link.'"></script>';

        echo $scripts_html;
        echo $links_html;
    endif;
    ?>


    <!--END OF DEV-->
    <link rel="stylesheet" href="css/default.css">
    <script src="../dist/dbManager/ng.js"></script>
    <script src="../dist/dbManager/services.js"></script>
    <script src="directives/pagination.directive.js"></script>
</head>
<body>

<ui-view>

</ui-view>


<script>
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
</script>
</body>
</html>
