<?php
require '../dbManager/tpl/login/formLogin.php';

class Register extends Forms
{
    public $userName,$userEmail,$userPass,$emailRegExp,$unameRegExp;
    function __construct()
    {
        parent::__construct();
        $this->unameRegExp = '/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/';
        $this->emailRegExp = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';
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
        $parentArr["newUserEmail"] = $this->con->real_escape_string($newUE);
        return $parentArr;
    }
    function RegExpCheck($para,$val){
        if($para === 'userName'){
            $reg = $this->unameRegExp;
        }elseif ($para === 'email'){
            $reg = $this->emailRegExp;
        }
        return isset($reg)?preg_match($reg,$val):false;
    }
    function isUsed($para,$val){
        if($this->RegExpCheck($para,$val)) {
            $res = $this->con->query("SELECT * FROM users WHERE $para = '$val'");
        }
        return isset($res)?$res->num_rows?'1':'0':'fail';

    }

    function insertReg($newUN, $newUE, $newUP)
    {
        $con = $this->con;
            $arr = $this->validate_userUEP($newUN, $newUE, $newUP);
            $newUserName = $arr["userName"];
            $newUserEmail = $arr["newUserEmail"];
            $newUserPass = $arr["userPassword"];
            $response = ["warnings"=>[]];
            if($this->isUsed('userName',$newUserName)) $response['warnings'][]= $newUserName . ' is already used';
            if($this->isUsed('email',$newUserEmail)) $response['warnings'][]= $newUserEmail . ' is already used';
            if(count($response['warnings']) > 0) {
                $response['mes'] = 'false';
                exit(json_encode($response));
            }else{
                if($file_name = check_image('profile_img')){
                    echo 'true3';
                    $imgName = move_uploaded_file($_FILES['profile_img']['tmp_name'], 'assets/img/profiles/'. $file_name)?$file_name:'';
                }
                $imgName = isset($imgName)?$imgName:'';
                $con->query("INSERT INTO users(userName,email,password) VALUES('$newUserName','$newUserEmail','$newUserPass')");
                $regQuery = $con->query("SELECT id,userName,email FROM users WHERE userName = '$newUserName'");
                if ($row = mysqli_fetch_assoc($regQuery)) {
                    echo 'row-is-here';
                    $id = $row['id'];
                    foreach ($_POST['optionals'] as $opt_key => $opt_val){
                        $_POST['optionals'][$opt_key] = !isset($_POST['optionals'][$opt_key])?'לא זמין':$con->real_escape_string($_POST['optionals'][$opt_key]);
                        $$opt_key = $opt_val;
                        echo 'in-each';
                    }
                    $rQue = "INSERT INTO reporters(user_id,first_name,last_name,age,city,private_phone,public_phone,profile_img) 
                                       VALUES($id,'$first_name','$last_name',$age,'$city','$private_phone','$public_phone','$imgName')";
                    $sets = $con->query("INSERT INTO userssettings(feedPostsNum,user_id) VALUES(10,$id)");
                    $rept = $con->query($rQue);
                    echo '<pre>';
                    var_dump($sets);
                    echo '</pre>';
                    echo '<pre>';
                    var_dump($rept);
                    echo '</pre>';
                    echo '<pre>';
                    var_dump($rQue);
                    echo '</pre>';
                    echo json_encode($response);
                }
            }
            $con->close();
    }
    function clean_optionals($collection){

    }
    function empty_missing_opts(){
        $_POST['first_name'] = isset($_POST['first_name'])?$_POST['first_name']:'';
        $_POST['last_name'] = isset($_POST['last_name'])?$_POST['last_name']:'';
        $_POST['age'] = isset($_POST['age'])?$_POST['age']:'';
        $_POST['city'] = isset($_POST['city'])?$_POST['city']:'';
        $_POST['private_phone'] = isset($_POST['private_phone'])?$_POST['private_phone']:'לא זמין';
        $_POST['public_phone'] = isset($_POST['public_phone'])?$_POST['public_phone']:'לא זמין' ;

    }

}
if (isset($_POST['check_uname']) || isset($_POST['check_email'])) {
    $regRequest = new Register();
    if(isset($_POST['check_uname'])) echo $regRequest->isUsed('userName',$_POST['check_uname']);
    if(isset($_POST['check_email'])) echo $regRequest->isUsed('email',$_POST['check_email']);
}
/*
if(isset($_POST['reg'])){
    $regRequest = new Register();
    if($regRequest->issetFullReg())$regRequest->insertReg($regRequest->userName, $regRequest->userEmail, $regRequest->userPass);
}
*/


function check_image($input_n){
    $ex = ['png','jpeg','jpg','gif','bmp'];
    if(isset($_FILES[$input_n]['error']) && $_FILES[$input_n]['error'] == 0){
        if(isset($_FILES[$input_n]['size']) && $_FILES[$input_n]['size'] <= MAX_FILE_SIZE){
            if(isset($_FILES[$input_n]['name'])){
                $file_info = pathinfo($_FILES[$input_n]['name']);
                if( in_array(strtolower($file_info['extension']),$ex)){
                    if( isset($_FILES[$input_n]['tmp_name']) && is_uploaded_file($_FILES[$input_n]['tmp_name'])){
                        return date('Y.m.d.H.i.s') . '-' . $_FILES[$input_n]['name'];
                    }
                }
            }

        }

    }
}