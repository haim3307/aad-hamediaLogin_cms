<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 17/12/2017
 * Time: 00:16
 */

class Connection
{
    static public $con;
    public $host;
    function __construct()
    {
        $this->host = $_SERVER['HTTP_HOST'].':3306';
        try{
            self::$con = new PDO('mysql:host='.$this->host.';dbname=aadhamedina;charset=utf8', 'root' , '');
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