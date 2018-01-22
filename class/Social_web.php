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
    static function add_new_post($content)
    {

        $content = htmlentities($content);

        $insert = self::query('INSERT INTO posts(title, front_img, author, description, activated, official,uid) 
        VALUES(:title,:front_img,:author,:description,:activated,:official,:uid)',
            [
                ':title' => $content,
                ':front_img' => '',
                ':author' => $_SESSION['front_user_name'],
                ':description' => '',
                ':activated' => 1,
                ':official' => 0,
                ':uid' => $_SESSION['front_user_id']


            ]


        );
        if ($insert->rowCount()) {
            $post_q = self::connect()->query('SELECT * FROM posts WHERE id=' . self::connect()->lastInsertId());
            if ($post = $post_q->fetch(PDO::FETCH_ASSOC)) {
                return $post;
            }
        } else var_dump($insert);
        return false;
    }

    static function get_posts($page)
    {
        $limit = 5;
        $start = (int)$page * $limit;
        $q = self::connect()->query("SELECT * FROM posts ORDER BY added_date DESC LIMIT $start,$limit");
        return isset($_GET['list'])&& $_GET['list'] == 'posts'?$q->fetchAll(PDO::FETCH_ASSOC):$q;
    }
}

if (isset($_POST['act'])) {
    Social_web::set_session();
    if (isset($_SESSION['front_user_id'])) {
        switch ($_POST['act']) {
            case 'new_post':
                if (isset($_POST['content'])) {
                    if ($res = Social_web::add_new_post($_POST['content'])) {
                        echo json_encode($res);
                    }
                }
                break;
        }
    }

}
if(isset($_GET['act'])){
    session_start();
    if (isset($_SESSION['front_user_id'])) {
        switch ($_GET['act']) {
            case 'get-posts':
                echo json_encode(Social_web::get_posts($_GET['feed-page']));
                break;
        }
    }
}
//var_dump($_POST);
//var_dump($_SESSION);
