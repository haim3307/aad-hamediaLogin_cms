<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 21/01/2018
 * Time: 15:59
 */
require_once 'Login.php';

class SocialWeb extends Login
{
    static function addNewPost($content, $posted_to = null)
    {
        $content = trim($content);
        if(!$content) return null;
        $date = gmdate('Y-m-d H:i:s');
        $content = filter_var($content,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $insert = self::query("INSERT INTO posts(title, front_img, activated, uid, added_date) 
        VALUES(:title,:front_img,:activated,:uid , '$date')",
            [
                ':title' => $content,
                ':front_img' => '',
                ':activated' => 1,
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
            return self::getPost($last_id,$posted_to);
        }
        return false;
    }
    static function getPost($post_id, $posted_to = null){
        if(!$posted_to){
            $st = 'SELECT p.* ,f.profile_img FROM posts p LEFT JOIN front_users f ON p.uid = f.id WHERE p.id=:pid';
            $args = [];
        }else if($posted_to = filter_var($posted_to,FILTER_VALIDATE_INT)){
            $st = 'SELECT p.*,f.name,po.to,f2.name _to,po.by FROM posts p JOIN posted po ON p.id = po.po_id JOIN front_users f ON p.uid = f.id JOIN front_users f2 ON po.to = f2.id
                WHERE p.id=:pid AND po.to=:po_to';
            $args = [':po_to'=>$posted_to];
        }
        if($post_id = filter_var($post_id,FILTER_VALIDATE_INT)){
            $args[':pid'] = $post_id;
            $post_q = self::query($st,$args);
            if ($post = $post_q->fetch(PDO::FETCH_ASSOC)) {
                $post['title'] = htmlentities($post['title']);
                $post['name'] = $_SESSION['front_user_name'];
                return $post;
            }
        }
        return false;
    }
    static function getPosts($page, $user_id = null)
    {
        if(filter_var($page,FILTER_VALIDATE_INT)){
            $pdo = self::connect();
            $limit = 5;
            $start = ((int)$page-1) * $limit;
            if(!isset($user_id)){
                $q = $pdo->query("SELECT p.*,f.profile_img, f.name FROM posts p LEFT JOIN front_users f ON p.uid = f.id ORDER BY added_date DESC LIMIT $start,$limit");
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
                    if(self::isLoggedIn()){
                        $all_likes_post_count_q = $pdo->query('SELECT COUNT(*) likes_of_post FROM liked_posts WHERE pid='.$row['id']);
                        $liked_by_you_q = $pdo->query('SELECT * FROM liked_posts WHERE pid='.$row['id'].' AND uid='.$_SESSION['front_user_id']);
                        $all_likes_post_q = $pdo->query('SELECT lp.*,u.name FROM liked_posts lp JOIN front_users u ON lp.uid = u.id WHERE lp.pid='.$row['id'].' AND u.id !='. $_SESSION['front_user_id'] .' LIMIT 10');
                        ;
                        $row['likes_count'] = ($likes_count = $all_likes_post_count_q->fetch()) && $likes_count[0]?$likes_count[0]:0;
                        $row['liked'] = $liked_by_you_q->fetch()?true:null;
                        $row['likers_list'] = ($likers_list = $all_likes_post_q->fetchAll(PDO::FETCH_ASSOC))?json_encode($likers_list):json_encode([]);
                    }else{
                        $row['liked'] = null;
                    }
                    $row['title'] = htmlspecialchars($row['title']);
                    $res[] = $row;
                }
            }
        }

        return isset($res)?$res:[];
    }
    static function deletePost($post_id){
        $q = self::query("DELETE FROM posts WHERE id =:post_id",[':post_id'=>((int)$post_id)]);
        return $q && $q->rowCount()?'deleted':'error';
    }
    static function updatePost($post_id, $post_title){
        if(isset($post_title) && filter_var($post_id,FILTER_VALIDATE_INT)){
            $post_title = trim($post_title);
            $post_title = filter_var($post_title,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
            if($post_title){
                $q = self::query("UPDATE posts SET title=:title WHERE id =:post_id AND uid=:uid",[':post_id'=>((int)$post_id),':title'=>$post_title,':uid'=>$_SESSION['front_user_id']]);
            }
        }
        return isset($q) && $q && $q->rowCount()?$post_title:'error';
    }
    static function is_following($follower_id,$followed_id){
        $q = self::query('SELECT fid FROM followers WHERE uid=:uid AND fid=:fid', [':uid' => $followed_id, ':fid' => $follower_id]);
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
    static function alreadyLiked($post_id){
        $q = self::query('SELECT * FROM liked_posts WHERE pid=:pid AND uid=:uid', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
        return $q->fetch()?true:false;
    }
    static function likePost($post_id){
        $liked = self::alreadyLiked($post_id);
        if(!$liked){
            $q = self::query('INSERT INTO liked_posts VALUES(\'\',:pid,:uid)', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
            return $q->rowCount()?'liked':'error';
        }else{
            $q = self::query('DELETE FROM liked_posts WHERE pid=:pid AND uid=:uid', [':uid' => $_SESSION['front_user_id'], ':pid' => $post_id]);
            return $q->rowCount()?'unliked':'error';
        }
    }
    static private function whoPosted($post_id){
        $q = self::query('SELECT uid FROM posts WHERE id=:pid',[':pid' => $post_id]);
    }
    static private function yourComment($commenter_id,$comment_id){
       return self::query('SELECT uid FROM posts_comments WHERE id=:cid AND uid=:uid',[':cid' => $comment_id,':uid'=>$commenter_id]);
    }
    static function getComments($post_id, $page = 0, $last_date = null, $first_date = null){
        $limit = 3;
        $start = $limit * $page;
        if(isset($last_date)){
            $if_last_date = " AND pc.added_date > '$last_date' ORDER BY pc.added_date DESC ";
        }elseif (isset($first_date)){
            $start = $limit * ($page-1);
            $if_last_date = " AND pc.added_date < '$first_date' ORDER BY pc.added_date DESC LIMIT $start,$limit";
        }else{
            $if_last_date = " ORDER BY pc.added_date DESC LIMIT $start,$limit";
        }
        //if($page !== 0) var_dump($if_last_date);
        if(filter_var($post_id,FILTER_VALIDATE_INT)) {
            $q = "SELECT pc.*,fu.profile_img, fu.name commenter_name FROM posts_comments pc JOIN front_users fu ON fu.id = pc.uid WHERE pc.pid=:pid $if_last_date";
            //var_dump($q);
            if($q = self::query($q,[':pid' => $post_id])){
                return ($res = $q->fetchAll(PDO::FETCH_ASSOC))?$res:[];
            }
        }
        return false;
    }
    static function addComment($post_id, $content){
        if(filter_var($post_id,FILTER_VALIDATE_INT)){
            if($content = filter_var($content,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES)){
                //self::is_following($_SESSION['front_user_id'],self::whoPosted($post_id))
                if(true){
                    if($insert = self::query('INSERT INTO posts_comments VALUES(\'\',:content,:pid,:uid,NOW())',
                        [':content'=>$content,':pid'=>$post_id,':uid'=>$_SESSION['front_user_id']]
                    )){
                        $con = self::connect();
                        $last_id = $con->lastInsertId();

                        if($get_new_comm_q = $con->query("SELECT * FROM posts_comments pc WHERE id=$last_id")){
                            $get_new_comm = $get_new_comm_q->fetch(PDO::FETCH_ASSOC);
                            $get_new_comm['profile_img'] = $_SESSION['front_profile_img'];
                            $get_new_comm['commenter_name'] = $_SESSION['front_user_name'];
                            return $get_new_comm;
                        }

                    }
                }
            }
        }
        return false;

    }
    static function deleteComment($comment_id){
        if(self::yourComment($_SESSION['front_user_id'],$comment_id)){
            $delete = self::query('DELETE FROM posts_comments WHERE id=:cid AND uid=:uid',[':cid' => $comment_id,':uid'=>$_SESSION['front_user_id']]);
            return $delete && $delete->rowCount();
        }else return false;
    }
    static function editComment($comment_id, $new_content){
        if(self::yourComment($_SESSION['front_user_id'],$comment_id)){
            $edit = self::query('UPDATE posts_comments SET content=:content WHERE id=:cid AND uid=:uid',
                [':cid' => $comment_id,':uid'=>$_SESSION['front_user_id'], ':content'=>$new_content]);
            if($edit && $edit->rowCount()){
                $get_new_content_q = self::query('SELECT content FROM posts_comments WHERE id=:cid AND uid=:uid',[':cid' => $comment_id,':uid'=>$_SESSION['front_user_id']]);
                if($get_new_content_q){
                    $get_new_content_r = $get_new_content_q->fetch();
                    if(isset($get_new_content_r['content'])) return $get_new_content_r['content'];
                }
            }
        }
        return false;
    }
}
if(isset($_GET['act'])){
    SocialWeb::setSession();
    if (isset($_SESSION['front_user_id'])) {
        switch ($_GET['act']) {
            case 'get_posts':
                echo json_encode(SocialWeb::getPosts($_GET['feed_page']));
                break;
        }
    }
}
//var_dump($_POST);
//var_dump($_SESSION);
