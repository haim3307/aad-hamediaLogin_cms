<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 20/01/2018
 * Time: 17:19
 */
require_once 'Connection.php';
class Forms extends Connection
{
    const MAX_FILE_SIZE = 1024*1024*20;
    static function checkImage($input_n){
        $ex = ['png','jpeg','jpg','gif','bmp'];
        if(isset($_FILES[$input_n]['error']) && $_FILES[$input_n]['error'] == 0){
            if(isset($_FILES[$input_n]['size']) && $_FILES[$input_n]['size'] <= self::MAX_FILE_SIZE){
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
        return false;
    }
    protected function cleanUserData($uname, $upass){
        $uname = parent::$con->quote($uname);
        $upass = md5(md5(parent::$con->quote($upass)));
        return ["userName" => $uname , "userPassword" => $upass];
    }
}