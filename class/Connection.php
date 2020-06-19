<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 17/12/2017
 * Time: 00:16
 */
require_once 'hosts.php';
date_default_timezone_set('Asia/Jerusalem');
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if($_SERVER['HTTP_HOST'] === 'localhost'){
    $root.= '/social_network';
}
require_once __DIR__.'/../includes/domain.php';

class Connection
{
    static protected $con;
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
                self::$con = new PDO('mysql:host='.MYSQL_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USERNAME , DB_PASSWORD);
                self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch (Exception $e){
                echo $e->getMessage();
            }
        }
        return self::$con;
    }
    static public function setSession($name = 'mysess'){
        if(!isset($_SESSION)){
            $path = '/fakebook';
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                $path='/social_network';
            }
            $time = null;
            session_set_cookie_params($time,$path);
            session_name($name);
            session_start();
            session_regenerate_id();

        }
    }

}