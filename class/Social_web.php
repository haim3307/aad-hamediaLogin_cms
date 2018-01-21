<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 21/01/2018
 * Time: 15:59
 */
require_once 'Connection.php';
class Social_web extends Connection
{
    static function add_new_post($content){

        $content = htmlentities($content);

        $insert = self::query('INSERT INTO posts(title, front_img, author, description, activated, official) 
        VALUES(:title,:front_img,:author,:description,:activated,:official)',
           [
               ':title'=>$content,
               ':front_img'=>'',
               ':author'=>$_SESSION['front_user_name'],
               ':description'=>'',
               ':activated'=>1,
               ':official'=>0,


           ]


        );
        if($insert->rowCount()){
            $post_q = self::connect()->query('SELECT * FROM posts WHERE id='.self::connect()->lastInsertId());
            if($post = $post_q->fetch(PDO::FETCH_ASSOC)){
                return $post;
            }
        }else var_dump($insert);
        return false;
    }
}
if(isset($_POST['act'])){
    session_start();
    if(isset($_SESSION['front_user_id'])){
        switch ($_POST['act']){
            case 'new_post':
                if(isset($_POST['content'])){
                    if($res = Social_web::add_new_post($_POST['content'])){
                        echo json_encode($res);
                    }
                }
        }
    }

}
//var_dump($_POST);
//var_dump($_SESSION);
