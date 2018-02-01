<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 01/02/2018
 * Time: 12:23
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/aad-hamediaLogin_cms';
}
