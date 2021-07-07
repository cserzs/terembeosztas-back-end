<?php
namespace App\Libraries;

class PDO {

    private static $pdo = null;
    
    public static function get() {
        if (self::$pdo == null) {
            PDO::init();
        }
        return self::$pdo;
    }

    private static function init() {
        $pdoOptions = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ); 
        /*
        self::$pdo = new \PDO('mysql:host=localhost;dbname=' . $_ENV['database.pdo.database'],
            $_ENV['database.pdo.username'],
            $_ENV['database.pdo.password'],
            $pdoOptions);
        */

        self::$pdo = new \PDO('mysql:host=localhost;dbname=' . \TBConfig::get("database.pdo.databasename"),
            \TBConfig::get("database.pdo.username"),
            \TBConfig::get("database.pdo.password"),
            $pdoOptions);

    }
}