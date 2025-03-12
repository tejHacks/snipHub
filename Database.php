<?php


class Database{

    private $host = "localhost";
    private $dbname = "code_snippets";
    private $username = "root";
    private $password = "";
    
    public $conn;

    public function __construct(){
        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Database connection failed " . $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->conn;
    }

}


?>