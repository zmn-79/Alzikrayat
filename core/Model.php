<?php

abstract class Model
{

    protected PDO $db;

    // إنشاء اتصال بقاعدة البيانات يشترك فيه كل الموديلز
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
