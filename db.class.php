<?php

/**
 * 
 * @package DBConnectOOPSPHP
 * @version 1.0
 * 
 * Database functions 
 * 
 * 
 * 
 */

class DB {
    
    private $conn;
    private $host;
    private $db_user;
    private $db_password;
    private $db_name;
    private $port;
    private $debug;
    
    //constructor
    function __construct($params=array()) {
        $this->conn = false;
        $this->host = 'your_database_host';
        $this->db_user = 'your_database_user_name';
        $this->db_password = 'your_database_password';
        $this->db_name = 'your_database_name';
        $this->port = '3306';
        $this->debug = true;
        $this->connect();
    }
    
    //destructor
    function __destruct() {
        $this->disconnect();
    }
    
    //function to connect to mysql database
    function connect() {
        
        if(!$this->conn) {
            $this->conn = mysqli_connect($this->host, $this->db_user, $this->db_password, $this->db_name);
        }
        if ($this->conn->connect_error) {
            $this->status_fatal = true;
            die("Connection failed: " . $this->conn->connect_error);
        } else {
            $this->status_fatal = false;
        }
        return $this->conn;			
    }
    
    //function to disconnect with database
    function disconnect() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    //function to get only one result from database
    function getOne($query) {        
        $cnx = $this->conn;
        if (!$cnx || $this->status_fatal) {
            echo 'GetOne -> Connection BDD failed';
            die();
        }    		
        $cur = $cnx->query($query);
        
        if($cur == FALSE) {
            $errorMessage = @pg_last_error($cnx);
            $this->handleError($query, $errorMessage);
        } else {
            $this->Error=FALSE;
            $this->BadQuery="";
            $tmp = $cur->fetch_assoc();    			
            $return = $tmp;
        }    		
        return $return;
    }
    
    //function to get all results from databse
    function getAll($query) {
        //var_dump($query);
        $cnx = $this->conn;
        if(!$cnx || $this->status_fatal) {
            echo 'GetAll -> Connection BDD failed';
            die();
        }
        $cur = $cnx->query($query);
        
        $return = array();
        while($data = mysqli_fetch_assoc($cur)) {
            array_push($return, $data);
        }
        return $return;
    }
    
    //function execute query like INSERT, UPDATE
    function execute($query, $use_slave=false) {
        
        $ctx = $this->conn;
        
        if ($ctx->query($query) === TRUE) {
            $this->Error=FALSE;
            $this->BadQuery="";
            $this->NumRows = mysqli_affected_rows($ctx);
            return;
        } else {
            $this->handleError($query, $ctx->error);
        }            
    }
    
    //function for debugging
    function handleError($query, $str_erreur) {
        $this->Error = TRUE;
        $this->BadQuery = $query;
        if ($this->debug) {
            echo "Query : ".$query."<br>";
            echo "Error : ".$str_erreur."<br>";
        }
    }
    
    //function to insert data by passing an argument of array type
    public function insert($table_name, $data) {    	    
        $ctx = $this->conn;
        
        $query = "INSERT INTO " . $table_name . " (";
        $query .= implode(",", array_keys($data)) . ') VALUES (';
        $query .= "'" . implode("','", array_values($data)) . "')";
        
        if ($ctx->query($query) === TRUE) {
            $this->Error=FALSE;
            $this->BadQuery="";
            $this->NumRows = mysqli_affected_rows($ctx);
            return true;
        } else {
            $this->handleError($query, $ctx->error);
        }            
    }
    
    
    //function to update data by passing an argument of array type
    public function update($table_name, $data) {
        
        $ctx = $this->conn;
        $ordata = $data;  
        unset($data['id']);
        
        $str = '';
        $i = 0;

        foreach(array_keys($data) as $temp) {
            $str .= array_keys($data)[$i] . "= '" . array_values($data)[$i] ."', ";
            $i++;
        }  

        $query = "UPDATE " . $table_name . " SET ";
        $query .= rtrim($str, ', ');
        $query .= " WHERE  id = " . array_values($ordata)[0];

        if ($ctx->query($query) === TRUE) {
            $this->Error=FALSE;
            $this->BadQuery="";
            $this->NumRows = mysqli_affected_rows($ctx);
            return true;
        } else {
            $this->handleError($query, $ctx->error);
        }            
    }
}
