<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 18:09
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/aad-hamediaLogin_cms';
}