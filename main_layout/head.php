<?php
require_once $root.'/includes/domain.php';
require_once $root.'/class/SocialWeb.php';
require_once $root.'/class/Login.php';
if($follower_id = Login::isLoggedIn()){
    $con = SocialWeb::connect();
    $con = SocialWeb::connect();

}else if(isset($_GET['app-page']) && $_GET['app-page'] !== 'home'){
    header('location:'.DOMAIN);

}

?>
<meta charset="UTF-8">
<link href="//fonts.googleapis.com/css?family=Suez+One" rel="stylesheet">
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/styles.css" />
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/font-awesome.css">
<link href="//use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if(!$is_local):?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/he.js"></script>

<?php else: ?>
<script src="<?= DOMAIN ?>/app/lib/jquery-3.2.1.js"></script>
<script src="<?= DOMAIN ?>/app/lib/http_code.jquery.com_ui_1.12.1_jquery-ui.js"></script>
<script src="<?= DOMAIN ?>/app/lib/moment.js"></script>
<?php endif; ?>
<script src="//use.fontawesome.com/3c576bb39d.js"></script>
<script src="<?= DOMAIN ?>_scripts/main.js"></script>