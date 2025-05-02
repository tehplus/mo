<?php
/**
 * کلاس اتصال به پایگاه داده
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 04:26:30
 */

class Database {
    private static $instance = null;
    private $conn = null;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . 
                   ";dbname=" . DB_NAME . 
                   ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_persian_ci"
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch(PDOException $e) {
            error_log(sprintf(
                "[%s] Database Connection Error: %s", 
                date('Y-m-d H:i:s'), 
                $e->getMessage()
            ));
            throw new Exception('خطا در اتصال به پایگاه داده');
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    private function __clone() {}
    
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    public function __destruct() {
        $this->conn = null;
    }
}