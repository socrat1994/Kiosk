<?php
namespace General\Database;

use General\EnvReader;

trait Connection {


  protected $conn;
  protected $dbname;

  public function connect() {
    try {
      $env = EnvReader::getInstance();
       $host = $env->get('HOST');
       $username = $env->get('USERNAME');
       $password = $env->get('PASSWORD');
       $this->dbname = $env->get('DBNAME');
      $conn = new \PDO("mysql:host=$host;dbname=$this->dbname", $username, $password,array(
          \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
      ));
      $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch(PDOException $e) {
      throw $e;
    }
  }

  public function disconnect() {
    $this->conn = Null;
  }
}
