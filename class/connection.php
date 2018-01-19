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
        define('DB_CONNECTION','mysql');
        define('DB_HOST','mysql');
        define('DB_PORT','3306');
        define('DB_DATABASE','aadhamedina');
        if($_SERVER['HTTP_HOST'] == 'localhost'){
            $this->host = $_SERVER['HTTP_HOST'];
            define('DB_USERNAME','root');
            define('DB_PASSWORD','');
        }else{
            $this->host = 'localhost:3306';
            define('DB_USERNAME','haimt');
            define('DB_PASSWORD','957846213Ha');
        }
        try{
            self::$con = new PDO('mysql:host='.$this->host.';dbname='.DB_DATABASE.';charset=utf8', DB_USERNAME , DB_PASSWORD);
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