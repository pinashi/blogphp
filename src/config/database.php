<?php

Class Database {
    private static $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $env = parse_ini_file(__DIR__ . '/../.env');

            self::$connection = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
                $env['DB_USER'],
                $env['DB_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
    
        return self::$connection;
    }
}