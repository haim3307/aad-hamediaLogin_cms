<?php
require_once 'session.php';
if(isset($_SESSION['loggedIN']))echo $_SESSION['loggedIN'];
else echo '0';
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 29/11/2017
 * Time: 00:06
 */
