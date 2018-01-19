<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 17/12/2017
 * Time: 00:16
 */
require_once 'hosts.php';
class Connection
{
    static public $con;
    public $host;
    function __construct()
    {


        try{
            self::$con = new PDO('mysql:host=localhost:3306;dbname='.DB_DATABASE.';charset=utf8', DB_USERNAME , DB_PASSWORD);
            self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
    static function query($query,$params){
        $stmt = self::$con->prepare($query) or die("query failed");
        $stmt->execute($params);
        return $stmt;
    }

}