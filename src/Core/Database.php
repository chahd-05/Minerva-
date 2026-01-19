<?php
namespace App\Core;

class Database{
    private static $pdo = null;
    public static function getPDO(){
        if(self::$pdo === null){
            $config = require __DIR__ . '/../../config/database.php';
            $db = $config['db'];
            self::$pdo = new \PDO($db['dsn'], $db['user'], $db['password'], $db['options']);
          
        }
        return self::$pdo;
    }
}