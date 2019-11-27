<?php

class config {

    private $host;
    private $user;
    private $password;
    private $db;
    private $dns;
    //private $option;
    public function  __construct() {
        $this->host='localhost';
        $this->user='root';
        $this->password='4ND1N4%2016$';
        $this->db='cob_andina';
		$this->port='3306';
        $this->dns='mysql:dbname='.($this->db).';port='.($this->port).';host='.($this->host);
        /*$this->host='localhost';
        $this->user='root';
        $this->password='';
        $this->db='testCobrast';
        $this->dns='mysql:dbname=testCobrast;host=localhost';*/
        //$this->option=array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_PERSISTENT => true);
    }
    public function setHost ( $host ) {
        $this->host=$host;
    }
    public function getHost () {
        return $this->host;
    }

    public function setUser ( $user ) {
        $this->user=$user;
    }
    public function getUser ( ) {
        return $this->user;
    }

    public function setPassword ( $password ) {
        $this->password=$password;
    }
    public function getPassword () {
        return $this->password;
    }

    public function setDb ( $db ) {
        $this->db=$db;
    }
    public function getDb ( ) {
        return $this->db;
    }

    public function setDns ( $dns ) {
        $this->dns=$dns;
    }
    public function getDns ( ) {
        return $this->dns;
    }

    /*public function setOption ( $option ) {
			$this->option=$option;
		}
		public function getOption ( ) {
			return $this->option;
		}*/
}


//Jennifer 15-12-2014 
class ConexionBD 
{
     const SERVER = "localhost";
     const USER = "root";
     const PASS = "";
     const DATABASE = "cob_andina";
     const port='3306';
     private $cn = null;
     public function getConexionBD()
     {
        
        try{
           
            $this->cn = @mysql_connect(self::SERVER, self::USER, self::PASS, self::port);
               
                @mysql_select_db(self::DATABASE, $this->cn);               
            }
                catch(Exception $e)
                {               
                }        
        
        return $this->cn;
    }      
}
//Jennifer 15-12-2014 **



?>
