<?php
/**
 * کلاس اتصال به پایگاه داده
 * @author tehplus
 * @version 1.0.1
 * @since 2025-05-02 10:37:59
 */

class Database {
    private static $instance = null;
    private $conn = null;
    
    private function __construct() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_persian_ci"
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // تست اتصال
            $this->conn->query('SELECT 1');
            
        } catch(PDOException $e) {
            error_log(sprintf(
                "[%s] Database Connection Error: %s in %s on line %d", 
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
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
        if ($this->conn === null) {
            throw new Exception('اتصال به پایگاه داده برقرار نیست');
        }
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