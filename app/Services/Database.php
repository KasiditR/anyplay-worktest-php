<?php
namespace App\Services;

class Database
{
    private static $_mysqli = null;

    public static function connect()
    {
        if (self::$_mysqli === null) {
            $config = require __DIR__ . "/../../config/database.php";

            self::$_mysqli = new \mysqli($config["host"], $config["username"], $config["password"], $config["dbname"]);

            if (self::$_mysqli->connect_error) {
                die('Connect Error (' . self::$_mysqli->connect_error . ') ' . self::$_mysqli->connect_error);
            }
        }

        return self::$_mysqli;
    }
}

?>