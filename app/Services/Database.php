<?php
namespace App\Services;

class Database
{
    private static $mysqli = null;

    public static function connect()
    {
        if (self::$mysqli === null) {
            $config = require __DIR__ . "/../../config/database.php";

            self::$mysqli = new \mysqli($config["host"], $config["username"], $config["password"], $config["dbname"]);

            if (self::$mysqli->connect_error) {
                die('Connect Error (' . self::$mysqli->connect_error . ') ' . self::$mysqli->connect_error);
            }
        }

        return self::$mysqli;
    }
}

?>