<?php
require_once 'Connection.php';
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 18/11/2017
 * Time: 16:18
 */
//var_dump($_FILES);
$params = json_decode(file_get_contents('php://input'), true);

if (isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileName = str_replace(' ', '-', trim($fileName));
    move_uploaded_file($_FILES['file']['tmp_name'], "../_img/$fileName");
    echo json_encode($fileName);
}
if (isset($_FILES['summer_file'])) {
    $fileName = $_FILES['summer_file']['name'];
    $fileName = str_replace(' ', '-', trim($fileName));
    move_uploaded_file($_FILES['summer_file']['tmp_name'], "../_img/report/upload/$fileName");
    echo json_encode($fileName);

}

class titlesTraffic extends Connection
{
    private static $quantity = 5;
    private static $feedPostsNum = 5;

    function __construct()
    {

        parent::__construct();
        $query = parent::$con->query('SELECT comments_quan FROM userssettings WHERE user_id=1');
        self::$quantity = $query->fetch(PDO::FETCH_NUM)[0];
        $query = parent::$con->query("SELECT feedPostsNum FROM userssettings WHERE user_id=1");
        self::$feedPostsNum = $query->fetch(PDO::FETCH_NUM)[0];
    }

    function getCommQuan()
    {
        return self::$quantity;
    }

    function setCommQuan(int $quan)
    {
        parent::$con->query("UPDATE userssettings SET comments_quan=$quan WHERE user_id=1");
        titlesTraffic::$quantity = $quan;
        echo 'quan result : ' . titlesTraffic::$quantity;
    }

    function getFeedPostsNum()
    {
        return self::$feedPostsNum;
    }

    function setFeedPostsNum(int $quan)
    {
        parent::$con->query("UPDATE userssettings SET feedPostsNum=$quan WHERE user_id=1");
        titlesTraffic::$feedPostsNum = $quan;
        echo 'quan result : ' . titlesTraffic::$feedPostsNum;
    }


    function getArticle($artId, $artTitle)
    {
        $query = parent::$con->query("SELECT * FROM articles WHERE id=$artId") OR die("sql failed");
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if (isset($_GET['full-art']))
            $res['full-art'] = file_get_contents('../articles/html/' . $res['article']);
        //isset($_GET['full-art'])?$res['full-art']:'המאמר ריק';
        return $res;
    }

    function create_art($title, $art_article, $reporterName, $artDesc, $frontImg, $activated, $tags = null)
    {
        if (isset($tags)) $tags = implode("*", $tags);
        $frontImg = $this->update_front_art_image($frontImg, '../_img/report/postFront/');
        $article = $this->set_article_file($art_article, '../articles/html/');
        $stmt = parent::query(
            'INSERT INTO articles(title, reporterName ,description, frontImg , article , tags, activated)  
            values(:title,:reporterName,:description,:frontImg,:article,:tags,:activated)',
            [':title'=>$title,':reporterName'=>$reporterName,':description'=>$artDesc,':frontImg'=>$frontImg,':article'=>$article,':tags'=>$tags,':activated'=>$activated]

        );
        if ($stmt->fetch()) {
            $art_id = parent::$con->lastInsertId();
            $official = 1;
            parent::query(
                'INSERT INTO posts(title, author ,description, front_img , official, activated,art_id)  values(:title,:author,:description,:frontImg,:official,:activated,:art_id)',
                [':title'=>$title, ':author'=>$reporterName, ':description'=>$artDesc, ':frontImg'=>$frontImg, ':official'=>$official, ':activated'=>$activated, ':art_id'=>$art_id]
            );
            echo 'upload success';
        };

    }

    function edit_art($art_id, $fImage_Name, $frontImg = null, $art_file_name, $art_article, $title, $reporterName, $artDesc, $activated, $tags)
    {
        if (isset($tags)) $tags = implode("*", $tags);
        if (isset($frontImg)) $this->update_front_art_image($frontImg, '../_img/report/postFront/', $fImage_Name);
        $article = $this->set_article_file($art_article, '../articles/html/', $art_file_name);
        $stmt = parent::query(
            'UPDATE articles SET title = :title, reporterName = :reporterName,description = :description, frontImg = :frontImg, article = :article, tags = :tags, activated = :activated WHERE id=:id',
            [':title'=>$title,':reporterName'=>$reporterName,':description'=>$artDesc,':frontImg'=>$frontImg,':article'=>$article,':tags'=>$tags,':activated'=>$activated,':id'=>$art_id]
        );
        if ($stmt->fetch()) echo 'update success';
        else echo 'update failed';
    }

    function get_articles($postStart = null, $getLen = null)
    {
        if (isset($getLen)) $artsLen = $this->get_table_and_search_len('articles');
        $postStart = !isset($postStart) ? 0 : $postStart * self::$feedPostsNum;
        $query = parent::$con->query('SELECT * FROM articles ORDER BY addedDate DESC LIMIT ' . $postStart . ',' . self::$feedPostsNum);
        return isset($_GET['list']) ? ["artArr" => $query->fetchAll(PDO::FETCH_ASSOC), "artLen" => isset($artsLen) ? $artsLen : 0, "artsQuan" => self::$feedPostsNum] : $query;
    }

    function quick_search_arts($searchText)
    {
        $searchText = parent::$con->quote("%$searchText%");
        $query = parent::$con->query("SELECT * FROM articles WHERE title LIKE $searchText ORDER BY title DESC LIMIT 5");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res : [];
    }

    function simular_articles($date)
    {
        $query = parent::$con->query("SELECT * FROM articles WHERE addedDate < '$date' ORDER BY addedDate LIMIT 10");
        if ($query->rowCount() < 1) {
            $query = parent::$con->query("SELECT * FROM articles ORDER BY addedDate LIMIT 10");
        }
        return isset($_GET['list']) ? ["artArr" => $query->fetchAll(PDO::FETCH_ASSOC), "artLen" => isset($artsLen) ? $artsLen : 0, "artsQuan" => self::$feedPostsNum] : $query;
    }
    //SELECT c.name ,AVG(p.price) avg_price FROM categories c JOIN products p ON c.id = p.categorie_id GROUP BY p.categorie_id

    function get_commenters()
    {
        $query = parent::$con->query('SELECT * FROM commenters LIMIT 10');
        return isset($_GET['list']) ? $query->fetchAll(PDO::FETCH_ASSOC) : $query;
    }

    function get_reporters($page = null, $searchText = '')
    {
        $totalLen = $this->get_table_and_search_len('reporters', null, $searchText, 'first_name');
        $start = $page ? ($page - 1) * self::$quantity : 0;
        $statement = "SELECT * FROM reporters";
        $statement .= " WHERE first_name LIKE '%$searchText%'";
        $statement .= " LIMIT $start , " . self::$quantity;
        //exit($statement);
        $query = parent::$con->query($statement);
        $res = $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
        return isset($_GET['list']) ? ['commArr' => $res, "totalLen" => $totalLen, "commQuan" => self::$quantity] : $query;
    }

    function get_table_and_search_len($tableName, $mode = null, $searchText = null, $prop = 'title')
    {
        $more = '';
        if (isset($mode) || isset($searchText)) $more = ' WHERE ';
        if (isset($mode)) $more .= "activated=$mode";
        if (isset($mode) && isset($searchText)) $more .= ' AND ';
        if (isset($searchText)) $more .= "$prop LIKE '%$searchText%'";
        $res = parent::$con->query("SELECT COUNT(*) FROM $tableName $more");
        return $res->fetch(PDO::FETCH_NUM)[0];
    }

    function get_comments($mode = null, $date = null, $page = null, $searchText = null)
    {
        $totalLen = $this->get_table_and_search_len('comments', $mode, $searchText);
        $statement = "SELECT * FROM comments";
        $max_limit = titlesTraffic::$quantity;
        if (isset($mode) || isset($searchText)) $statement .= ' WHERE ';
        if (isset($mode)) $statement .= " activated=$mode";
        if (!isset($page)) {
            $min_limit = 0;
        } else {
            $min_limit = titlesTraffic::$quantity * ($page - 1);
        }
        if (isset($searchText)) {
            if (isset($mode)) $statement .= " AND";
            $statement .= " title LIKE '%$searchText%'";
        }
        $allSt = $statement . " ORDER BY addedDate DESC LIMIT $min_limit,$max_limit";
        $query = parent::$con->query($allSt);
        if (isset($_GET['list'])) {
            $res = $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
            return ['commArr' => $res,
                'totalLen' => $totalLen,
                'rowsnum' => $query->rowCount(),
            ];
        }
        return $query;
    }

    function get_tags($page, $search)
    {
        $statement = "SELECT * FROM tags";
        $query = parent::$con->query($statement);
        if (isset($_GET['list'])) {
            $res = $query ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
            return ['commArr' => $res, 'rowsnum' => $query->rowCount()];
        }

        return $query;

    }

    function updateCommStatus($status, $comm_id, $date)
    {
        $stmt = parent::$con->prepare('UPDATE comments SET activated = ? WHERE id = ?');
        $stmt->bindValue(1, $status , PDO::PARAM_INT);
        $stmt->bindValue(2, $comm_id , PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            if (!$status) $status = 0;
            $item_from_next_page = titlesTraffic::$quantity - 1;
            $stmt1 = parent::$con->prepare("SELECT * FROM comments WHERE addedDate <= ? AND activated=? ORDER BY addedDate DESC LIMIT ? , ?");
            $stmt1->bindValue(1, $date , PDO::PARAM_STR);
            $stmt1->bindValue(2, $status , PDO::PARAM_INT);
            $stmt1->bindValue(3, $item_from_next_page , PDO::PARAM_INT);
            $stmt1->bindValue(4, titlesTraffic::$quantity , PDO::PARAM_INT);
            $stmt1->execute();
            $result = $stmt1->fetch(PDO::FETCH_ASSOC);
        }
        return isset($result) ? $result : [];
    }

    function delete_comment($comm_id, $date, $comm_status)
    {
        $stmt = parent::query(
            'DELETE FROM comments WHERE id=:id',
            [':id' => $comm_id]
        );
        if ($stmt->fetch()) {
            $item_from_next_page = titlesTraffic::$quantity - 1;
            $stmt1 = parent::query(
                'SELECT * FROM comments WHERE addedDate <= :addedDate AND activated=:activated ORDER BY addedDate DESC LIMIT :itfnp , :quan ?',
                [':addedDate' => $date,':activated'=>$comm_status,':itfnp'=>$item_from_next_page,':quan'=>titlesTraffic::$quantity]
            );
            $result = $stmt1->fetch(PDO::FETCH_ASSOC);
        }
        return isset($result) ? $result : [];
    }

    function delete_comment1($comm_id, $date, $comm_status, $comm_index)
    {
        $item_from_next_page = titlesTraffic::$quantity - $comm_index + 1;
        $stmt1 = parent::query(
            "SELECT * FROM comments WHERE addedDate <= :addedDate AND activated=:activated ORDER BY addedDate DESC LIMIT :itfp , :quantity",
            [':addedDate' => $date, ':activated' => $comm_status, ':itfp' => $item_from_next_page, ':quantity' => titlesTraffic::$quantity]
        );
        $result = $stmt1->fetch(PDO::FETCH_ASSOC);
        if ($result) parent::query('DELETE FROM comments WHERE id=:id', [':id' => $comm_id]);
        return $result ? $result : [];

    }

    function get_art_desc($id)
    {
        $stmt = parent::query('SELECT description FROM articles WHERE id = :id', [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }

    function get_title_id_list()
    {
        $query = parent::$con->query("SELECT * FROM categories_arts_ids") OR DIE('error idlists');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    static function get_title_page_articles()
    {
        $total_res = [];
        $query = parent::$con->query("
        SELECT a.id,a.title,a.frontImg,a.reporterName,a.addedDate,t.category_name,c.cat_id,c.art_order 
        FROM categories_arts_ids c,articles a,title_page t WHERE a.id=c.art_id AND c.cat_id = t.id
        ORDER BY cat_id,art_order") OR DIE('error idlists1');
        $cond = !isset($_GET['asArray']);
        $x=0;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $total_res[$row['category_name']][] = $cond?[$row['id'], $row]:$row;
            $x++;
        }


        return $total_res;

    }

    function delete_art($id)
    {
        $stmt = parent::query('DELETE FROM articles WHERE id = :id', [':id'=>$id]);
        echo $stmt->rowCount() ? 'deleted' : 'errorOfDelete';
    }

    function update_artFront($id, $title, $content, $img, $oldImg)
    {
        $fileName = isset($img) ? $this->update_front_art_image($img, '../_img/report/postFront/') : $oldImg;
        $stmt = parent::query(
            'UPDATE articles SET title=:title,frontImg=:frontImg,description=:description  WHERE id= :id',
            [':title' => $title, ':frontImg' => $fileName, ':description' => $content, ':id' => $id]
        );
        $res = ["mes" => $stmt->fetch() ? 'true' : 'false', "frontImgName" => $fileName];
        echo json_encode($res);
    }
    static function delete_cate_art(int $id,int $cate){
        $stmt = parent::$con->prepare("DELETE FROM categories_arts_ids WHERE art_id=? AND cat_id=?");
        $stmt->bindValue(1, $id , PDO::PARAM_INT);
        $stmt->bindValue(2, $cate , PDO::PARAM_INT);
        $stmt->execute();
        var_dump($stmt->rowCount());
        var_dump($stmt->rowCount());
        var_dump($id);
        var_dump($cate);
    }


    function update_art_status($id, $status)
    {
        $stmt = parent::$con->query(
            'UPDATE articles SET activated = :activated WHERE id = :id',
            [':id' => $id, ':status' => $status,]
        );
        $res = $stmt->fetch() ? 'true' : 'false';
        return $res;
    }

    function update_front_art_image($img, $path, $existFile = null)
    {

        $imgBase = substr(explode(";", $img['data'])[1], 7);
        if (!isset($existFile)) {
            $str = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $fileName = substr(str_shuffle($str), 0, 10);
            $fileEXT = explode("/", $img['type'])[1];
            $fileName .= "$fileName.$fileEXT";
        } else $fileName = $existFile;
        file_put_contents($path . $fileName, base64_decode($imgBase));

        return $fileName;
    }

    function update_title_page($category, $idList)
    {
        $idList = explode(',' ,$idList);
        echo $category;
        $query = parent::$con->query("SELECT id FROM title_page WHERE category_name = '$category'") OR DIE('error idlists');
        $category = $query->fetch(PDO::FETCH_ASSOC)['id'];
        $query1 = parent::$con->query("DELETE FROM categories_arts_ids WHERE cat_id = '$category'") OR DIE('error idlists1');
        if ($query->rowCount() == 1) {
            $str = '';
            $str.=" VALUES($idList[0],$category,0)";
            for($x=1; $x<sizeof($idList); $x++){
                $str.=" ,($idList[$x],$category,$x)";
            }
            $statement = "INSERT INTO categories_arts_ids(art_id,cat_id,art_order) $str";
            echo $statement;
            parent::$con->query($statement);
        }

    }

    function set_article_file($art, $path, $existFile = null)
    {
        if (!isset($existFile)) {
            $str = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $fileName = substr(str_shuffle($str), 0, 10);
            $fileName = "$fileName.html";
        } else $fileName = $existFile;

        file_put_contents($path . $fileName, "\xEF\xBB\xBF" . $art);
        return $fileName;
    }


}

$artObj = new titlesTraffic();
if (isset($_GET['list'])) {
    switch ($_GET['list']) {
        case 'art':
            if (!isset($_GET['feedPage'])) $_GET['feedPage'] = null;
            if (!isset($_GET['art-len'])) $_GET['art-len'] = null;
            $artList = $artObj->get_articles($_GET['feedPage'], $_GET['art-len']);
            break;
        case 'commenters':
            $artList = $artObj->get_commenters();
            break;
        case 'comments':
            function allNull()
            {
                if (!isset($_GET['comemode'])) $_GET['comemode'] = null;
                if (!isset($_GET['lastDate'])) $_GET['lastDate'] = null;
                if (!isset($_GET['search'])) $_GET['search'] = null;
                if (!isset($_GET['page'])) $_GET['page'] = null;
            }

            allNull();
            $artList = $artObj->get_comments($_GET['comemode'], $_GET['lastDate'], (int)$_GET['page'], $_GET['search']);
            break;
        case 'reporters':
            $artList = $artObj->get_reporters($_GET['page'], $_GET['query']);
            break;
        case 'tags':
            $artList = $artObj->get_tags(null, null);
            break;
        case 'cates_id_lists':
            $artList = titlesTraffic::get_title_page_articles();
    }
    echo json_encode($artList);

}
if (isset($params['act'])) {
    if ($params['act'] == 'update-com-status') {
        echo json_encode($artObj->updateCommStatus((int)$params['com-status'], (int)$params['comm-id'], $params['comm-date']));
    } elseif ($params['act'] == 'delete-comm') {
        echo json_encode($artObj->delete_comment((int)$params['comm-id'], $params['comm-date'], (int)$params['comm-status']));
    } elseif ($params['act'] == 'get-comm-quan') {
        echo $artObj->getCommQuan();
    } elseif ($params['act'] == 'update-comm-quan') {
        $artObj->setCommQuan((int)$params['comm-quantity']);

    } elseif ($params['act'] == 'update-art-front') {
        /*$params['artContent']*/
        $params['artFrontImg'] = isset($params['artFrontImg']) ? $params['artFrontImg'] : null;
        $params['artContent'] = !isset($params['artContent']) ? 'problem' : $params['artContent'];
        $artObj->update_artFront($params['artId'], $params['artTitle'], $params['artContent'], $params['artFrontImg'], $params['artOldFrontImg']);

    } elseif ($params['act'] == 'get-art-by-id') {
        echo json_encode($artObj->getArticle((int)$params['art-id'], null));
    } elseif ($params['act'] == 'update-art-status') {
        echo $artObj->update_art_status((int)$params['art_id'], (int)$params['art_status']);
    }

}
if (isset($_GET['act'])) {
    if ($_GET['act'] == 'get-art-by-id') {
        echo json_encode($artObj->getArticle((int)$_GET['art-id'], null));
    }
    elseif ($_GET['act'] == 'create-art' && isset($_GET['title']) && isset($params['article']) && isset($_GET['reporterName']) && isset($_GET['artDesc']) && $params['frontImg']) {
        $tags = isset($params['tags']) ? $params['tags'] : null;
        $activated = isset($_GET['saveArtOnClick']) ? 1 : 0;
        $artObj->create_art($_GET['title'], $params['article'], $_GET['reporterName'], $_GET['artDesc'], $params['frontImg'], $activated, $tags);
    } elseif ($_GET['act'] == 'edit-art') {
        $tags = isset($params['tags']) ? $params['tags'] : null;
        $activated = isset($_GET['saveArtOnClick']) ? 1 : 0;
        $frontImg = !isset($params['frontImg']) ? null : $params['frontImg'];
        $artObj->edit_art((int)$params['artId'], $params['frontImgName'], $frontImg, $params['artFileName'], $params['article'], $_GET['title'], $_GET['reporterName'], $_GET['artDesc'], $activated, $tags);
    } elseif ($_GET['act'] == 'quick-art-search') {
        echo json_encode($artObj->quick_search_arts($_GET['qt']));
    } elseif ($_GET['act'] == 'update-arts-id') {
        $artObj->update_title_page($_GET['category'], $_GET['order-ids']);
    }
}
if (!empty($_GET['id']) && isset($_GET['getDesc'])) {
    $art_desc = $artObj->get_art_desc($_GET['id']);
    echo $art_desc;
}
if (isset($_POST['deleteIt']) && !empty($_POST['id'])) {
    $artObj->delete_art((int)$_POST['id']);
}
if (isset($_POST['update']) && isset($_POST['title']) && isset($_POST['id'])) {
    $artObj->update_art($_POST['id'], $_POST['title']);
}
if (isset($_GET['artList'])) {
    $arts = $con->get_articles();
}
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    if(isset($_GET['act'])){
        switch ($_GET['act']){
            case 'delete-cate-art':
                titlesTraffic::delete_cate_art((int)$_GET['art-id'],(int)$_GET['category']);
        }
    }
}