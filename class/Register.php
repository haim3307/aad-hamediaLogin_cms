<?php
require 'Forms.php';

class Register extends Forms
{
    public $userName,$userEmail,$userPass;
    static public $unameRegExp = '/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/';
    static public $emailRegExp  = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';
    function __construct()
    {
        parent::__construct();
        if(isset($_POST['reg'])){
            $this->userName = $_POST['newUserName'];
            $this->userEmail = $_POST['newUserEmail'];
            $this->userPass = $_POST['newUserPass'];
        }

    }

    function issetFullReg()
    {
        return !empty($this->userName) && !empty($this->userEmail) && !empty($this->userPass);
    }

    private function validate_userUEP($newUN, $newUE, $newUP): array
    {
        $parentArr = parent::clean_userData($newUN,$newUP);
        $parentArr["newUserEmail"] = self::$con->quote($newUE);
        return $parentArr;
    }
    static function RegExpCheck($para,$val){

        if($para === 'userName'){
            $reg = self::$unameRegExp;
        }
        if ($para === 'email'){
            $reg = self::$emailRegExp;
        }

        return isset($reg)?preg_match($reg,$val):false;
    }
    static function isUsed($para,$val){
        //secho self::RegExpCheck($para,$val);
        var_dump(self::connect());
        if(self::RegExpCheck($para,$val)) {
            if($para === 'userName'){
                $column = 'name';
            }
            if($para === 'email'){
                $column = 'email';
            }
            if(isset($column)){
                $res = self::connect()->prepare("SELECT * FROM front_users WHERE $column = ?");
                var_dump($res);
                $res->bindParam(1,$val,PDO::PARAM_STR);
                $res->execute();
            }else return 'zzzz';

        }
        if(isset($res)){
            if($res->fetch()){
                echo 'used';
            }else echo 'not used';
        }
        //return isset($res)?$res->fetch()?'1':'0':'fail2';
        return true;
    }

    function insertReg($newUN, $newUE, $newUP)
    {
        $con = self::$con;
        $arr = $this->validate_userUEP($newUN, $newUE, $newUP);
        $newUserName = $arr["userName"];
        $newUserEmail = $arr["newUserEmail"];
        $newUserPass = $arr["userPassword"];
        $response = ["warnings"=>[]];
        if(self::isUsed('userName',$newUserName)) $response['warnings'][]= $newUserName . ' is already used';
        if(self::isUsed('email',$newUserEmail)) $response['warnings'][]= $newUserEmail . ' is already used';
        if(count($response['warnings']) > 0) {
            $response['mes'] = 'false';
            exit(json_encode($response));
        }else{
            if($file_name = self::check_image('profile_img')){
                echo 'true3';
                $imgName = move_uploaded_file($_FILES['profile_img']['tmp_name'], 'assets/img/profiles/'. $file_name)?$file_name:'';
            }
            $imgName = isset($imgName)?$imgName:'';
            $con->query("INSERT INTO users(userName,email,password) VALUES('$newUserName','$newUserEmail','$newUserPass')");
            $regQuery = self::$con->query("SELECT id,userName,email FROM users WHERE userName = '$newUserName'");
            if ($row = $regQuery->fetch(PDO::FETCH_ASSOC)) {
                echo 'row-is-here';
                $id = $row['id'];
                foreach ($_POST['optionals'] as $opt_key => $opt_val){
                    $_POST['optionals'][$opt_key] = !isset($_POST['optionals'][$opt_key])?'לא זמין':self::$con->quote($_POST['optionals'][$opt_key]);
                    $$opt_key = $opt_val;
                    echo 'in-each';
                }
                $rQue = "INSERT INTO reporters(user_id,first_name,last_name,age,city,private_phone,public_phone,profile_img) 
                                       VALUES($id,$first_name,$last_name,$age,$city,$private_phone,$public_phone,$imgName)";
                $sets = $con->query("INSERT INTO userssettings(feedPostsNum,user_id) VALUES(10,$id)");
                $rept = $con->query($rQue);
                /*                    echo '<pre>';
                                    var_dump($sets);
                                    echo '</pre>';
                                    echo '<pre>';
                                    var_dump($rept);
                                    echo '</pre>';
                                    echo '<pre>';
                                    var_dump($rQue);
                                    echo '</pre>';
                                    echo json_encode($response);*/
            }
        }
    }
    static function is_empty(array $params){

        if(empty($params['user_name'])) $errors['user_name']['empty'] = 'אנא מלא את שם המשתמש';
        elseif(self::validate_reg($params['user_name'],'user_name')) $errors['user_name']['invalid'] = 'שם המשתמש אינו תקין';
        if(empty($params['new_email'])) $errors['new_email']['empty'] = 'אנא מלא אמייל';
        elseif(self::validate_reg($params['new_email'],'email')) $errors['new_email']['invalid'] = 'אמייל זה אינו תקין';
        if(empty($params['password'])) $errors['password']['empty'] = 'אנא מלא סיסמא';
        elseif(self::validate_reg($params['password'],'password')) $errors['password']['invalid'] = 'הסיסמא אינה תקינה';
        if(empty($params['password1'])) $errors['password1']['empty'] = 'אנא מלא סיסמא בשנית';
        return isset($errors)?$errors:false;
    }
    static function validate_reg($param,$type){
        $reg_preg = [
            'user_name'=>'/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/',
            'new_email'=>'/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i',
            'password'=>'/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
        ];
        echo $param,$type;
        switch ($type){
            case 'user_name':
                $preg = 'user_name';
                break;
            case 'new_email':
                $preg = 'new_email';
                break;
            case 'password': $preg = 'password';
        }
        return isset($preg)?preg_match($reg_preg[$preg],$param):false;

    }

}
/*if (isset($_POST['check_uname']) || isset($_POST['check_email'])) {
    if(isset($_POST['check_uname'])) echo Register::isUsed('userName',$_POST['check_uname']);
    if(isset($_POST['check_email'])) echo Register::isUsed('email',$_POST['check_email']);
}*/
if(isset($_POST['act'])){
    if($_POST['act'] === 'check_user_name'){
        echo Register::isUsed('userName',$_POST['check_user_name']);
    }
    if($_POST['act'] === 'check_new_email'){
        echo Register::isUsed('email',$_POST['check_new_email']);
    }
}
/*
if(isset($_POST['reg'])){
    $regRequest = new Register();
    if($regRequest->issetFullReg())$regRequest->insertReg($regRequest->userName, $regRequest->userEmail, $regRequest->userPass);
}
*/

