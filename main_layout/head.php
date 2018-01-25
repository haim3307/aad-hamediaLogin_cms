<?php
$is_local = $_SERVER['HTTP_HOST'] == 'localhost';
if($is_local){
    define('DOMAIN','http://localhost/aad-hamediaLogin_cms/');
}else{
    define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
}


?>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/styles.css" />
<link rel="stylesheet" href="<?= DOMAIN ?>styles/_css/font-awesome.css">
<link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if(!$is_local):?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<?php else: ?>
<script src="<?= DOMAIN ?>/dbManager/lib/jquery-3.2.1.js"></script>
<script src="<?= DOMAIN ?>/dbManager/lib/http_code.jquery.com_ui_1.12.1_jquery-ui.js"></script>
<?php endif; ?>

<script src="<?= DOMAIN ?>_scripts/main.js"></script>