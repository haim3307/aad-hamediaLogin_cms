<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 17/12/2017
 * Time: 00:16
 */
require_once 'hosts.php';
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/social_network';
}
require_once $root.'/includes/domain.php';

class Connection
{
    static public $con;
    public $host;
    function __construct()
    {
        self::connect();
    }
    static function query($query,$params){
        $stmt = self::connect()->prepare($query) or die("query failed");
        $stmt->execute($params);
        return $stmt;
    }
    static function connect(){
        if(!isset(self::$con)){
            try{
                self::$con = new PDO('mysql:host=localhost:3306;dbname='.DB_DATABASE.';charset=utf8', DB_USERNAME , DB_PASSWORD);
                self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch (Exception $e){
                echo $e->getMessage();
            }
            //echo 'con';
        }
        return self::$con;
    }
    static public function set_session(){
        if(!isset($_SESSION)){
            $path = '/';
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                $path='/social_network';
            }
            //$time = 60*60*24*7;
            $time = null;
            session_set_cookie_params($time,$path);
            session_start();
            session_regenerate_id();

        }
    }

}