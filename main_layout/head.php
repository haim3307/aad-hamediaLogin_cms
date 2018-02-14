<?php
/*if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die (header( 'HTTP/1.0 403 Forbidden', TRUE, 403 ));
}*/
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/social_network';
}
require_once $root.'/includes/domain.php';
require_once $root.'/class/SocialWeb.php';
require_once $root.'/class/Login.php';
if($follower_id = Login::isLoggedIn()){
    $con = SocialWeb::connect();

}else if(isset($_GET['app-page']) && $_GET['app-page'] !== 'home'){
    header('location:'.DOMAIN);

}

?>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/styles.css" />
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/font-awesome.css">
<link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if(!$is_local):?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<?php else: ?>
<script src="<?= DOMAIN ?>/app/lib/jquery-3.2.1.js"></script>
<script src="<?= DOMAIN ?>/app/lib/http_code.jquery.com_ui_1.12.1_jquery-ui.js"></script>
<?php endif; ?>

<script src="<?= DOMAIN ?>_scripts/main.js"></script>