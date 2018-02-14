<?php
require 'Forms.php';

class Register extends Forms
{
    public $userName, $userEmail, $userPass;
    static public $unameRegExp = '/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/';
    static public $emailRegExp = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';

    function __construct()
    {
        parent::__construct();
        if (isset($_POST['reg'])) {
            $this->userName = $_POST['newUserName'];
            $this->userEmail = $_POST['newUserEmail'];
            $this->userPass = $_POST['newUserPass'];
        }

    }

    function issetFullReg()
    {
        return !empty($this->userName) && !empty($this->userEmail) && !empty($this->userPass);
    }

    static function RegExpCheck($para, $val)
    {

        if ($para === 'userName') {
            $reg = self::$unameRegExp;
        }
        if ($para === 'email') {
            $reg = self::$emailRegExp;
        }

        return isset($reg) ? preg_match($reg, $val) : false;
    }

    static function isUsed($para, $val)
    {
        if (self::validateReg($val, $para)) {
            if ($para === 'userName') {
                $column = 'name';
            }
            if ($para === 'email') {
                $column = 'email';
            }
            if (isset($column)) {
                $res = self::connect()->prepare("SELECT * FROM front_users WHERE $column = ?");
                $res->bindParam(1, $val, PDO::PARAM_STR);
                $res->execute();
            } else return false;

        }
        if (isset($res)) {
            if ($res->fetch()) {
                return 'used';
            } else return 'not used';
        }
        return false;
    }

    static function insertRegStat($newUN, $newUE, $newUP)
    {
        $con = self::connect();
        $response = ["warnings" => []];
        if (self::isUsed('userName', $newUN) == 'used') $response['warnings'][] = $newUN . ' is already used';
        if (self::isUsed('email', $newUE) == 'used') $response['warnings'][] = $newUE . ' is already used';
        if (!count($response['warnings'])) {
            $user = [
                ':user_name'=>$newUN,
                ':email' =>$newUE,
                ':password' => password_hash($newUP,PASSWORD_BCRYPT)
            ];
            if(self::query('INSERT INTO front_users(name, password, email) VALUES(:user_name,:password,:email)',$user)){
                $_SESSION['new_user_id'] = self::connect()->lastInsertId();
                $_SESSION['new_user_name'] = $newUN;
                $_SESSION['new_email'] = $newUE;
                $_SESSION['new_pass'] = $newUP;
            }


            return true;
        } else return $response['warnings'];

    }
    static function checkRegisterSession(){
        return isset($_SESSION['new_user_name']) && isset($_SESSION['new_email']) && isset($_SESSION['new_pass']);
    }

    static function isEmpty(array $params)
    {
        if (empty($params['user_name'])) $errors['user_name']['empty'] = 'אנא מלא את שם המשתמש';
        elseif (!self::validateReg($params['user_name'], 'user_name')) $errors['user_name']['invalid'] = 'שם המשתמש אינו תקין';
        if (empty($params['new_email'])) $errors['new_email']['empty'] = 'אנא מלא אמייל';
        elseif (!self::validateReg($params['new_email'], 'new_email')) $errors['new_email']['invalid'] = 'אמייל זה אינו תקין';
        if (empty($params['password'])) $errors['password']['empty'] = 'אנא מלא סיסמא';
        elseif (!self::validateReg($params['password'], 'password')) $errors['password']['invalid'] = 'הסיסמא אינה תקינה';
        if (empty($params['password1'])) $errors['password1']['empty'] = 'אנא מלא סיסמא בשנית';
        if ($params['password1'] !== $params['password']) $errors['password1']['invalid'] = "הסיסמאות אינם תואמות";
        return isset($errors) ? $errors : false;
    }

    static function validateReg($param, $type)
    {
        $reg_preg = [
            'user_name' => '/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/',
            'new_email' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i',
            'password' => '/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
        ];
        //echo $param,$type;
        switch ($type) {
            case 'user_name':
            case 'userName':
                $preg = 'user_name';
                break;
            case 'new_email':
            case 'email':
                $preg = 'new_email';
                break;
            case 'password':
                $preg = 'password';
        }
        return isset($preg) ? preg_match($reg_preg[$preg], $param) : false;

    }

}

if (isset($_POST['act'])) {
    if ($_POST['act'] === 'check_user_name') {
        echo Register::isUsed('userName', $_POST['check_user_name']);
    }
    if ($_POST['act'] === 'check_new_email') {
        echo Register::isUsed('email', $_POST['check_new_email']);
    }
}


