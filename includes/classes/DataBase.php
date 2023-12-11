<?php 
class DataBase{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '123';
    private $database = 'panel';
 
    protected $connection;
    public function __construct(){
        if (!isset($this->connection)) {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->connection->connect_error) {
                Utils::add_problem("DataBase connect failed [".$this->connection->connect_error."]",3);
                die('Connect Error, '. $this->connection->connect_errno . ': ' . $this->connection->connect_error);
            }
        }    
        return $this->connection;
    }
//    public function connection(){
//        if (isset($this->connection)) {
//            return $this->connection;
//        } else {
//            $this->__construct();
//        }
//    }
}