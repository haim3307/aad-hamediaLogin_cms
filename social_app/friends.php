<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 14:36
 */
if($q1 = $con->query("SELECT u.name,u.id FROM followers f JOIN front_users u ON u.id = f.uid WHERE fid=$follower_id")){
    $following_list = $q1->fetchAll(PDO::FETCH_ASSOC);
}
if($q2 = $con->query("SELECT u.name,u.id FROM followers f JOIN front_users u ON u.id = f.uid WHERE uid=$follower_id")){
    $followed_by_list = $q2->fetchAll(PDO::FETCH_ASSOC);
}
?>
<style>
    .friendsPage{

    }
    .followLists{
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
</style>
<div class="friendsPage">
    <div class="followLists">
        <div>
            <h1>
                אתה עוקב אחרי
            </h1>
            <ul>
                <?php foreach ($following_list as $following):?>
                <li><?= $following['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h1>עוקבים אחרייך</h1>
            <ul>
                <?php foreach ($followed_by_list as $followed_by):?>
                    <li><?= $followed_by['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>