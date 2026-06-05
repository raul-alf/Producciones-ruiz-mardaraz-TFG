<?php

class Database
{
    private static $host = 'db5019517939.hosting-data.io';
    private static $dbname = 'dbs15258558';
    private static $user = 'dbu2058633';
    private static $password = 'LaLuna2026.';
    private static $charset = 'utf8mb4';

    public static function connect()
    {
        static $pdo = null;

        if ($pdo === null) {

            $dsn = "mysql:host=" . self::$host .
                   ";dbname=" . self::$dbname .
                   ";charset=" . self::$charset;

            $pdo = new PDO(
                $dsn,
                self::$user,
                self::$password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        }

        return $pdo;
    }
}