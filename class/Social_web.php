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
            $post_q = self::connect()->query('SELECT p.* ,f.profile_img FROM posts p LEFT JOIN front_users f ON p.uid = f.id WHERE p.id=' . self::connect()->lastInsertId());
            if ($post = $post_q->fetch(PDO::FETCH_ASSOC)) {
                $post['title'] = htmlentities($post['title']);
                return $post;
            }
        } else var_dump($insert);
        return false;
    }

    static function get_posts($page)
    {
        $limit = 5;
        $start = (int)$page * $limit;
        $q = self::connect()->query("SELECT p.*,f.profile_img FROM posts p LEFT JOIN front_users f ON p.uid = f.id ORDER BY added_date DESC LIMIT $start,$limit");
        if($q){
            $res = [];
            while ($row = $q->fetch(PDO::FETCH_ASSOC)){
                $row['title'] = htmlspecialchars($row['title']);
                $res[] = $row;
            }
        }

        return isset($res)?$res:[];
    }
    static function delete_post($post_id){
        $q = self::query("DELETE FROM posts WHERE id =:post_id",[':post_id'=>((int)$post_id)]);
        return $q && $q->rowCount()?'deleted':'error';
    }
    static function is_following($follower_id,$user_id){
        $q = self::query('SELECT fid FROM followers WHERE uid=:uid AND fid=:fid', [':uid' => $user_id, ':fid' => $follower_id]);
        return $q?$q->fetch():false;
    }
    static function follow($follower_id,$user_id){
        $is_following = self::is_following($follower_id,$user_id);
        if (!($is_following)) {
            return self::query('INSERT INTO followers VALUES(\'\', :uid,:fid)', [':uid' => $user_id, 'fid' => $follower_id]);

        } else {
            return !self::query('DELETE FROM followers WHERE uid=:uid AND fid=:fid', [':uid' => $user_id, 'fid' => $follower_id]);
        }
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
    Social_web::set_session();
    if (isset($_SESSION['front_user_id'])) {
        switch ($_GET['act']) {
            case 'get_posts':
                echo json_encode(Social_web::get_posts($_GET['feed_page']));
                break;
        }
    }
}
//var_dump($_POST);
//var_dump($_SESSION);
