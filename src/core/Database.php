<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require_once __DIR__ . '/../../.config/database.php';
        
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        
        try {
            $this->connection = new PDO($dsn, $config['user'], $config['pass'], $options);
        } catch (PDOException $e) {
            // Lança a exceção para que o código que chama a classe possa tratá-la.
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}