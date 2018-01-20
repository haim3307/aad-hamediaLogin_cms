<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 20/01/2018
 * Time: 17:13
 */
require_once '../../class/Register.php';
class CMS_Register extends Register
{
    function empty_missing_opts(){
        $_POST['first_name'] = isset($_POST['first_name'])?$_POST['first_name']:'';
        $_POST['last_name'] = isset($_POST['last_name'])?$_POST['last_name']:'';
        $_POST['age'] = isset($_POST['age'])?$_POST['age']:'';
        $_POST['city'] = isset($_POST['city'])?$_POST['city']:'';
        $_POST['private_phone'] = isset($_POST['private_phone'])?$_POST['private_phone']:'לא זמין';
        $_POST['public_phone'] = isset($_POST['public_phone'])?$_POST['public_phone']:'לא זמין' ;

    }
}