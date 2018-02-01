<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 21/01/2018
 * Time: 15:59
 */
require_once 'Login.php';

class Social_web extends Login
{
    static function add_new_post($content,$posted_to = null)
    {

        $content = htmlentities($content);

        $insert = self::query('INSERT INTO posts(title, front_img, activated, official, uid) 
        VALUES(:title,:front_img,:activated,:official,:uid)',
            [
                ':title' => $content,
                ':front_img' => '',
                ':activated' => 1,
                ':official' => 0,
                ':uid' => $_SESSION['front_user_id']
            ]
        );
        $last_id = self::connect()->lastInsertId();
        if($posted_to){
           $insert1 = self::query('INSERT INTO posted(`by`,`to`,po_id) VALUES(:posted_by,:posted_to,:po_id)',
               [':posted_by'=>(int)$_SESSION['front_user_id'] , ':posted_to'=>(int)$posted_to,':po_id'=>(int)$last_id]
           );
        }
        if ($insert->rowCount()) {
            return self::get_post($last_id,$posted_to);
        } else var_dump($insert);
        return false;
    }
    static function get_post($post_id,$posted_to = null){
        if(!$posted_to){
            $st = 'SELECT p.* ,f.profile_img FROM posts p LEFT JOIN front_users f ON p.uid = f.id WHERE p.id=:pid';
            $args = [];
        }else{
            $st = 'SELECT p.*,f.name,po.to,f2.name _to,po.by FROM posts p JOIN posted po ON p.id = po.po_id JOIN front_users f ON p.uid = f.id JOIN front_users f2 ON po.to = f2.id
            WHERE p.id=:pid AND po.to=:po_to';
            $args = [':po_to'=>$posted_to];
        }
        $args[':pid'] = $post_id;
        $post_q = self::query($st,$args);
        if ($post = $post_q->fetch(PDO::FETCH_ASSOC)) {
            $post['title'] = htmlentities($post['title']);
            $post['name'] = $_SESSION['front_user_name'];
            return $post;
        }else return false;
    }
    static function get_posts($page,$user_id = null)
    {
        $pdo = self::connect();
        $limit = 5;
        $start = (int)$page * $limit;
        if(!isset($user_id)){
            $if_not_logged = !self::isLoggedIn()?'':'WHERE official = 1';
            $q = $pdo->query("SELECT p.*,f.profile_img, f.name FROM posts p LEFT JOIN front_users f ON p.uid = f.id $if_not_logged ORDER BY added_date DESC LIMIT $start,$limit");
        }else{
            $q = $pdo->query(
            "SELECT p.*,fu.name name,fu.profile_img,f.name _to,po.to 
            FROM posted po LEFT JOIN front_users f ON po.to = f.id
            RIGHT JOIN posts p ON po.po_id = p.id 
            LEFT JOIN front_users fu ON p.uid = fu.id 
            WHERE p.uid =  $user_id OR po.to = $user_id 
            ORDER BY added_date DESC LIMIT $start,$limit"
            );

        }
        if($q){
            $res = [];
            while ($row = $q->fetch(PDO::FETCH_ASSOC)){
                if(isset($_SESSION['front_user_id'])){
                    $liked_post_q = $pdo->query('SELECT * FROM liked_posts WHERE pid='.$row['id'].' AND uid='.$_SESSION['front_user_id']);
                    $row['liked'] = $liked_post_q->fetch()?true:null;
                }else{
                    $row['liked'] = null;
                }
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
    static function already_liked($post_id){
        $q = self::query('SELECT * FROM liked_posts WHERE pid=:pid AND uid=:uid', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
        return $q->fetch()?true:false;
    }
    static function like_post($post_id){
        $liked = self::already_liked($post_id);
        if(!$liked){
            $q = self::query('INSERT INTO liked_posts VALUES(\'\',:pid,:uid)', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
            return $q->rowCount()?'liked':'error';
        }else{
            $q = self::query('DELETE FROM liked_posts WHERE pid=:pid AND uid=:uid', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
            return $q->rowCount()?'unliked':'error';
        }
    }
}

if (isset($_POST['act'])) {
    Social_web::set_session();
/*    if (isset($_SESSION['front_user_id'])) {
        switch ($_POST['act']) {
            case 'new_post':
                if (isset($_POST['content'])) {
                    if ($res = Social_web::add_new_post($_POST['content'])) {
                        echo json_encode($res);
                    }
                }
                break;
        }
    }*/

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
