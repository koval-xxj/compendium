<?php

/**
 * Description of DB
 *
 * @author Sergey K.
 */
class DB {
  
  //static protected $_instance;

  protected static $instance;
  protected $pdo;

  protected function __construct($host, $username, $password, $db_name) {
    $opt  = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_EMULATE_PREPARES   => FALSE,
    ];
    $dsn = 'mysql:host=' . $host . ';dbname=' . $db_name . ';charset=utf8';
    $this->pdo = new PDO($dsn, $username, $password, $opt);
  }

  // a classical static method to make it universally available
  public static function instance($host, $username, $password, $db_name) {
    if (self::$instance === null) {
      self::$instance = new self($host, $username, $password, $db_name);
    }
    return self::$instance;
  }
  
  public function check_tables() {
    $aTables = ['groups', 'products'];
    $aResult = $this->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    
    $aCheck = array_diff($aTables, $aResult);
    if (count($aCheck)) {
      throw new Exception('Required tables doesn\'t exists: ' . implode(', ', $aCheck));
    }
  }

// a proxy to native PDO methods
  public function __call($method, $args) {
    return call_user_func_array([$this->pdo, $method], $args);
  }
  
  public function insert($table, $aData) {
    $aKeys = array_keys($aData);
    $stmt = $this->pdo->prepare("INSERT INTO " . $table . " (" . implode(',', $aKeys) . ") VALUES (:" . implode(',:', $aKeys) . ")");
    $stmt -> execute($aData);
  }
  
}