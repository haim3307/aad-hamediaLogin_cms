<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:21
 */
/*echo '<pre style="direction: ltr;">';
var_export($_SERVER);
echo '</pre>';*/
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/aad-hamediaLogin_cms';
}
$is_local = $_SERVER['HTTP_HOST'] == 'localhost';
if($is_local){
    define('DOMAIN','https://localhost/aad-hamediaLogin_cms/');

}else{
    define('DOMAIN','https://'.$_SERVER['HTTP_HOST'].'/');

}