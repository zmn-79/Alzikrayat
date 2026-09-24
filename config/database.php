<?php

class Database
{

    private static ?Database $instance = null;

    private PDO $connection;

    private const DB_HOST = '127.0.0.1';
    private const DB_NAME = 'alzikrayat';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    // فتح اتصال واحد فقط بقاعدة البيانات (Singleton)
    private function __construct()
    {
        $dsn = 'mysql:host=' . self::DB_HOST .
               ';dbname=' . self::DB_NAME .
               ';charset=' . self::DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
    }

    // إرجاع نفس نسخة الاتصال في كل مرة بدل ما نفتح اتصال جديد
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // إرجاع اتصال PDO عشان الموديلز تستخدمه
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
