<?php

class Database
{
    private static $host = 'localhost';
    private static $dbname = 'cafe_luna'; // cambia esto por tu BD real
    private static $user = 'raul';
    private static $password = '';
    private static $charset = 'utf8mb4';

    public static function connect()
    {
        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;

        return new PDO($dsn, self::$user, self::$password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}