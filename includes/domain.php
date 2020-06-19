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
    $root.= '/social_network';
}else  $root.= '/fakebook/';
$is_local = $_SERVER['HTTP_HOST'] == 'localhost';
if($is_local){
    define('DOMAIN','http://localhost/social_network/');

}else{
    //define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
    define('DOMAIN','//haim-taragano.tk/fakebook/');

}